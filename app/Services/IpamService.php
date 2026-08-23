<?php

namespace App\Services;

use App\Models\IpAddress;

class IpamService
{
    private const MIN_PREFIX = 24;
    private const MAX_PREFIX = 30;

    // validasi format CIDR. mambalikan pesan erro jika salah, null kalau valid
    public function validateCidr(string $cidr) {
        if (! preg_match('#^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/(\d{1,2})$#', $cidr, $m)) {
            return 'Format CIDR tidak valid, contoh: 10.10.1.0/24.';
        }

        foreach ([$m[1], $m[2], $m[3], $m[4]] as $octet) {
            if ((int) $octet > 255) {
                return 'Format CIDR tidak valid, tiap oktet IP maksimal 255.';
            }
        }

        $prefix = (int) $m[5];

        if($prefix < self::MIN_PREFIX || $prefix > self::MAX_PREFIX) {
            return 'Prefix CIDR harus antara /'.self::MIN_PREFIX.' sampai /'.self::MAX_PREFIX.' (subnet lebih besar dari itu kepanjangan buat digenerate langsung).';
        }

        return null;
    }

    // Mengubah CIDR jadi daftar IP yang bisa dipakai (network & broadcast address dibuang)
    public function generateAddresses(string $cidr): array {
        [$network, $prefix] = explode('/', $cidr);
        $prefix = (int) $prefix;

        $networkLong = ip2long($network);
        $hostBits = 32 - $prefix;
        $total = 2 ** $hostBits;

        $start = $total > 2 ? 1 : 0;
        $end = $total > 2 ? $total - 1 : $total;

        $addresses = [];
        for ($i = $start; $i < $end; $i++) {
            $addresses[] = long2ip($networkLong + $i);
        }

        return $addresses;
    }

    // insert massa daftar ip ke 1 subnet, memakai insert() bukan create agar sekalian 1 query
    public function bulkCreateAddresses(int $subnetId, array $addresses): void {
        $now = now();

        $row = array_map(fn (string $address) => [
            'subnet_id' => $subnetId,
            'address' => $address,
            'status' => 'available',
            'created_at' => $now,
            'updated_at' => $now,
        ], $addresses);

        foreach (array_chunk($row, 500) as $chunk) {
            IpAddress::insert($chunk);
        }
    }

    // cek apakah sebuah IP boleh di assign ke sebuah port
    public function checkAssignable(IpAddress $ipAddress, int $devicePortId): ?string {
        if ($ipAddress->status !== 'available') {
            return "IP {$ipAddress->address} sedang berstatus \"{$ipAddress->status}\", tidak bisa di-assign.";
        }

        $portAlreadyHasIp = IpAddress::where('device_port_id', $devicePortId)
        ->where('status', 'assigned')
        ->exists();

        if ($portAlreadyHasIp) {
            return "Port ini sudah punya IP lain yang aktif, relase dulu sebelum assign IP baru";
        }

        return null;
    } 
}
