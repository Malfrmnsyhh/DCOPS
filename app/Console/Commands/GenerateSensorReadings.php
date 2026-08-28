<?php

namespace App\Console\Commands;

use App\Models\Alarm;
use App\Models\AlarmRule;
use App\Models\Sensor;
use App\Models\SensorReading;
use Illuminate\Console\Command;

class GenerateSensorReadings extends Command
{
    protected $signature = 'sensors:generate {--backfill= : Isi N readings ke belakang (interval dihitung dari jumlah menit / count). Contoh: --backfill=288 untuk 24 jam @ 5 menit}';
    protected $description = 'Generate reading palsu untuk semua sensor aktif, lalu evaluasi terhadap alarm rule';

    public function handle(): int
    {
        $sensors = Sensor::where('status', 'active')->get();
        $backfill = $this->option('backfill');

        if ($backfill !== null) {
            return $this->handleBackfill($sensors, (int) $backfill);
        }

        // Mode normal: satu reading per sensor untuk waktu sekarang
        foreach ($sensors as $sensor) {
            $value = $this->generateValue($sensor->type);
            $now = now();

            try {
                SensorReading::create([
                    'sensor_id' => $sensor->id,
                    'value'     => $value,
                    'recorded_at' => $now,
                ]);
            } catch (\Illuminate\Database\UniqueConstraintViolationException) {
                $this->warn("Sensor {$sensor->code}: reading di detik ini sudah ada, dilewati.");
                continue;
            }

            $sensor->update(['last_reading_at' => $now]);
            $this->evaluateAlarms($sensor, $value);
        }

        $this->info("Reading digenerate untuk {$sensors->count()} sensor.");

        return self::SUCCESS;
    }

    /**
     * Isi data historis sejumlah $count titik ke belakang.
     * Interval antar titik = 24 jam / $count (dalam menit, dibulatkan ke menit penuh).
     * Contoh: --backfill=288 → 288 titik, interval 5 menit, rentang 24 jam lalu s.d. sekarang.
     */
    private function handleBackfill($sensors, int $count): int
    {
        if ($count <= 0) {
            $this->error('--backfill harus berupa bilangan bulat positif.');
            return self::FAILURE;
        }

        // Hitung interval: 24 jam (1440 menit) dibagi jumlah titik
        $intervalMinutes = (int) round(1440 / $count);
        if ($intervalMinutes < 1) {
            $intervalMinutes = 1;
        }

        $this->info("Backfill: {$count} readings per sensor, interval {$intervalMinutes} menit.");

        $bar = $this->output->createProgressBar($sensors->count() * $count);
        $bar->start();

        $rows = [];
        $now = now();

        foreach ($sensors as $sensor) {
            for ($i = $count - 1; $i >= 0; $i--) {
                $recordedAt = $now->copy()->subMinutes($i * $intervalMinutes);
                $rows[] = [
                    'sensor_id'   => $sensor->id,
                    'value'       => $this->generateValue($sensor->type),
                    'recorded_at' => $recordedAt->toDateTimeString(),
                ];
                $bar->advance();
            }
            // Update last_reading_at sensor ke waktu paling baru
            $sensor->update(['last_reading_at' => $now]);
        }

        $bar->finish();
        $this->newLine();

        // Bulk insert per chunk (hindari query terlalu besar)
        foreach (array_chunk($rows, 500) as $chunk) {
            SensorReading::insert($chunk);
        }

        // Evaluasi alarm berdasarkan reading terbaru per sensor
        foreach ($sensors as $sensor) {
            $latest = SensorReading::where('sensor_id', $sensor->id)
                ->orderByDesc('recorded_at')
                ->value('value');
            if ($latest !== null) {
                $this->evaluateAlarms($sensor, (float) $latest);
            }
        }

        $this->info("Backfill selesai: " . count($rows) . " readings diinsert.");

        return self::SUCCESS;
    }

    private function generateValue(string $type): float
    {
        return match ($type) {
            'temperature' => round(mt_rand(180, 380) / 10, 2), // 18.0 - 38.0 °C
            'humidity' => round(mt_rand(200, 800) / 10, 2),    // 20.0 - 80.0 %
            'power' => round(mt_rand(500, 5000) / 10, 2),      // 50.0 - 500.0 W
            'airflow' => round(mt_rand(100, 1000) / 10, 2),    // 10.0 - 100.0
            default => round(mt_rand(0, 1000) / 10, 2),
        };
    }

    private function evaluateAlarms(Sensor $sensor, float $value): void
    {
        $rules = AlarmRule::where('is_active', true)
            ->where(function ($query) use ($sensor) {
                $query->where('sensor_id', $sensor->id)
                    ->orWhere(function ($query) use ($sensor) {
                        $query->whereNull('sensor_id')->where('sensor_type', $sensor->type);
                    });
            })
            ->get();

        foreach ($rules as $rule) {
            if (! $this->violatesRule($rule, $value)) {
                continue;
            }

            $hasActiveAlarm = Alarm::where('alarm_rule_id', $rule->id)
                ->whereIn('status', ['open', 'acknowledged'])
                ->exists();

            if ($hasActiveAlarm) {
                continue;
            }

            Alarm::create([
                'alarm_rule_id' => $rule->id,
                'sensor_id' => $sensor->id,
                'triggered_value' => $value,
                'severity' => $rule->severity,
                'status' => 'open',
                'triggered_at' => now(),
            ]);
        }
    }

    private function violatesRule(AlarmRule $rule, float $value): bool
    {
        return match ($rule->operator) {
            '>' => $rule->threshold_max !== null && $value > $rule->threshold_max,
            '>=' => $rule->threshold_max !== null && $value >= $rule->threshold_max,
            '<' => $rule->threshold_min !== null && $value < $rule->threshold_min,
            '<=' => $rule->threshold_min !== null && $value <= $rule->threshold_min,
            default => false,
        };
    }
}
