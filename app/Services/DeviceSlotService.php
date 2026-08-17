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
}
?>