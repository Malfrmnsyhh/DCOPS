<?php

namespace App\Services;

use App\Models\PortConnection;

class PortConnectionService
{
    /**
     * Cek apakah 2 port boleh disambungkan. Balikin pesan error kalau salah satu
     * port sudah kepakai di koneksi lain (baik sebagai from maupun to), null kalau aman.
     */
    public function checkPortsAvailable(int $fromPortId, int $toPortId, ?int $excludeConnectionId = null): ?string
    {
        if ($fromPortId === $toPortId) {
            return 'Port tidak bisa disambungkan ke dirinya sendiri.';
        }

        $conflict = PortConnection::query()
            ->where(function ($query) use ($fromPortId, $toPortId) {
                $query->whereIn('from_port_id', [$fromPortId, $toPortId])
                      ->orWhereIn('to_port_id', [$fromPortId, $toPortId]);
            })
            ->when($excludeConnectionId, fn ($query) => $query->whereKeyNot($excludeConnectionId))
            ->first();

        if ($conflict) {
            $usedPortId = in_array($fromPortId, [$conflict->from_port_id, $conflict->to_port_id])
                ? $fromPortId
                : $toPortId;

            return "Port {$usedPortId} sudah tersambung ke kabel lain.";
        }

        return null;
    }
}
