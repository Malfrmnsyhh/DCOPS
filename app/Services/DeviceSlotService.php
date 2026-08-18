<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Rack;

class DeviceSlotService
{
    public function checkSlotAvailability(Rack $rack, int $positionU, int $uSize, ?int $excludeDeviceId = null): ?string
    {
        $start = $positionU;
        $end = $positionU + $uSize - 1;

        // 1. Validasi batas atas rack (tetap sama)
        if ($end > $rack->u_height) {
            return "Slot U {$start}-{$end} melebihi tinggi rack ({$rack->u_height}U).";
        }

        // 2. Cek tabrakan langsung di level database menggunakan query SQL
        $collision = Device::query()
            ->where('rack_id', $rack->id)
            ->whereNotNull('position_u')
            ->when($excludeDeviceId, fn ($query) => $query->whereKeyNot($excludeDeviceId))
            // Menghitung $existingEnd langsung di database: (position_u + u_size - 1)
            ->where(function ($query) use ($start, $end) {
                $query->whereRaw('position_u + u_size - 1 >= ?', [$start])
                      ->where('position_u', '<=', $end);
            })
            ->first(); // Menggunakan database first(), bukan collection first()

        // 3. Jika ditemukan data yang menabrak aturan matematika tersebut
        if ($collision) {
            return "Slot U {$start}-{$end} sudah terisi device \"{$collision->hostname}\".";
        }

        return null;
    }

    /**
     * Susun peta isi rack per slot U (dari U1 sampai u_height), buat endpoint elevation.
     */
    public function buildElevation(Rack $rack): array
    {
        $devices = Device::query()
            ->where('rack_id', $rack->id)
            ->whereNotNull('position_u')
            ->get(['id', 'hostname', 'position_u', 'u_size', 'status']);

        $slots = [];

        for ($u = 1; $u <= $rack->u_height; $u++) {
            $occupant = $devices->first(
                fn (Device $device) => $u >= $device->position_u && $u <= ($device->position_u + $device->u_size - 1)
            );

            $slots[] = [
                'position_u' => $u,
                'device' => $occupant ? [
                    'id' => $occupant->id,
                    'hostname' => $occupant->hostname,
                    'u_size' => $occupant->u_size,
                    'status' => $occupant->status,
                    'is_start' => $u === $occupant->position_u,
                ] : null,
            ];
        }

        return $slots;
    }
}
?>