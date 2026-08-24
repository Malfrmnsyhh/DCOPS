<?php

namespace Database\Seeders;

use App\Models\AlarmRule;
use App\Models\Device;
use App\Models\DevicePort;
use App\Models\DeviceType;
use App\Models\PortConnection;
use App\Models\Rack;
use App\Models\Room;
use App\Models\Sensor;
use App\Models\Site;
use App\Models\Subnet;
use App\Services\IpamService;
use Illuminate\Database\Seeder;

class DcopsDummySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Device types (fixed & predictable, biar gampang diinget pas testing)
        $server = DeviceType::factory()->create([
            'name' => 'Dell PowerEdge R740', 'slug' => 'dell-poweredge-r740',
            'category' => 'server', 'default_u_size' => 2,
        ]);
        $switch = DeviceType::factory()->create([
            'name' => 'Cisco Catalyst 9300', 'slug' => 'cisco-catalyst-9300',
            'category' => 'network', 'default_u_size' => 1,
        ]);
        $storage = DeviceType::factory()->create([
            'name' => 'NetApp FAS2750', 'slug' => 'netapp-fas2750',
            'category' => 'storage', 'default_u_size' => 4,
        ]);

        // 2. Site -> Room -> Rack
        $siteJkt = Site::factory()->create(['code' => 'JKT-01', 'name' => 'Jakarta Data Center']);
        Site::factory()->create(['code' => 'SBY-02', 'name' => 'Surabaya Data Center']);
        Site::factory()->create(['code' => 'BDG-03', 'name' => 'Bandung Data Center']);

        $roomA = Room::factory()->create(['site_id' => $siteJkt->id, 'code' => 'RM-A', 'name' => 'Server Room A']);
        $roomB = Room::factory()->create(['site_id' => $siteJkt->id, 'code' => 'RM-B', 'name' => 'Server Room B']);

        $rack1 = Rack::factory()->create(['room_id' => $roomA->id, 'code' => 'RCK-001', 'name' => 'Rack 01']);
        $rack2 = Rack::factory()->create(['room_id' => $roomA->id, 'code' => 'RCK-002', 'name' => 'Rack 02']);
        $rack3 = Rack::factory()->create(['room_id' => $roomB->id, 'code' => 'RCK-001', 'name' => 'Rack 01']);

        // 3. Device ditempatkan di rack1, slot U sengaja tidak nabrak satu sama lain
        //    (slot 1-2, 3, 5-8 kepakai — slot 4 dan 9-42 sengaja dikosongkan buat bahan test)
        $deviceA = Device::factory()->create([
            'rack_id' => $rack1->id, 'device_type_id' => $server->id,
            'hostname' => 'srv-web-01', 'position_u' => 1, 'u_size' => 2, 'status' => 'active',
        ]);
        $deviceB = Device::factory()->create([
            'rack_id' => $rack1->id, 'device_type_id' => $switch->id,
            'hostname' => 'sw-core-01', 'position_u' => 3, 'u_size' => 1, 'status' => 'active',
        ]);
        $deviceC = Device::factory()->create([
            'rack_id' => $rack1->id, 'device_type_id' => $storage->id,
            'hostname' => 'str-main-01', 'position_u' => 5, 'u_size' => 4, 'status' => 'active',
        ]);

        // device belum dirak, di gudang (rack_id & position_u null)
        $deviceD = Device::factory()->create([
            'rack_id' => null, 'device_type_id' => $server->id,
            'hostname' => 'srv-standby-01', 'position_u' => null, 'status' => 'standby',
        ]);

        // 4. Port per device (nama deterministik: eth0, eth1, [mgmt buat switch])
        foreach ([$deviceA, $deviceB, $deviceC, $deviceD] as $device) {
            DevicePort::factory()->create(['device_id' => $device->id, 'name' => 'eth0']);
            DevicePort::factory()->create(['device_id' => $device->id, 'name' => 'eth1']);
        }
        DevicePort::factory()->create([
            'device_id' => $deviceB->id, 'name' => 'mgmt', 'speed_mbps' => 100,
        ]);

        // 5. Sambungkan sebagian port — sisanya sengaja dibiarkan nganggur buat bahan test
        $portA0 = DevicePort::where('device_id', $deviceA->id)->where('name', 'eth0')->first();
        $portB0 = DevicePort::where('device_id', $deviceB->id)->where('name', 'eth0')->first();
        $portB1 = DevicePort::where('device_id', $deviceB->id)->where('name', 'eth1')->first();
        $portC0 = DevicePort::where('device_id', $deviceC->id)->where('name', 'eth0')->first();

        PortConnection::factory()->create([
            'from_port_id' => $portA0->id, 'to_port_id' => $portB0->id,
            'cable_type' => 'cat6', 'cable_label' => 'LNK-001',
        ]);
        PortConnection::factory()->create([
            'from_port_id' => $portB1->id, 'to_port_id' => $portC0->id,
            'cable_type' => 'fiber', 'cable_label' => 'LNK-002',
        ]);

        // 6. Subnet + IP (IPAM) — pakai IpamService yang sama dengan endpoint asli,
        //    biar cara generate-nya konsisten (network/broadcast dibuang, dst).
        $subnet = Subnet::factory()->create([
            'name' => 'Management Subnet', 'cidr' => '10.10.1.0/24',
            'gateway' => '10.10.1.1', 'vlan_id' => 100,
        ]);

        $ipamService = app(IpamService::class);
        $ipamService->bulkCreateAddresses($subnet->id, $ipamService->generateAddresses($subnet->cidr));

        // 7. Sensor tersebar di 3 rack (kode deterministik biar gampang dites manual)
        $sensorTemp1 = Sensor::factory()->create([
            'rack_id' => $rack1->id, 'code' => 'TEMP-01', 'name' => 'Sensor Suhu Rack 1',
            'type' => 'temperature', 'unit' => '°C', 'status' => 'active',
        ]);
        Sensor::factory()->create([
            'rack_id' => $rack1->id, 'code' => 'HUM-01', 'name' => 'Sensor Kelembapan Rack 1',
            'type' => 'humidity', 'unit' => '%RH', 'status' => 'active',
        ]);
        $sensorPower1 = Sensor::factory()->create([
            'rack_id' => $rack1->id, 'code' => 'PWR-01', 'name' => 'Sensor Daya Rack 1',
            'type' => 'power', 'unit' => 'W', 'status' => 'active',
        ]);
        Sensor::factory()->create([
            'rack_id' => $rack2->id, 'code' => 'TEMP-02', 'name' => 'Sensor Suhu Rack 2',
            'type' => 'temperature', 'unit' => '°C', 'status' => 'active',
        ]);
        Sensor::factory()->create([
            'rack_id' => $rack2->id, 'code' => 'HUM-02', 'name' => 'Sensor Kelembapan Rack 2',
            'type' => 'humidity', 'unit' => '%RH', 'status' => 'active',
        ]);
        Sensor::factory()->create([
            'rack_id' => $rack3->id, 'code' => 'TEMP-03', 'name' => 'Sensor Suhu Rack 3',
            'type' => 'temperature', 'unit' => '°C', 'status' => 'active',
        ]);

        // 8. Alarm rule — threshold_max sengaja rendah di rule rack 1 biar hampir pasti
        //    kepicu begitu "php artisan sensors:generate" pertama kali dijalankan.
        AlarmRule::factory()->create([
            'sensor_id' => $sensorTemp1->id, 'sensor_type' => null,
            'name' => 'Suhu Rack 1 Kritis', 'operator' => '>', 'threshold_max' => 25,
            'severity' => 'critical', 'is_active' => true,
        ]);
        AlarmRule::factory()->create([
            'sensor_id' => $sensorPower1->id, 'sensor_type' => null,
            'name' => 'Daya Rack 1 Warning', 'operator' => '>', 'threshold_max' => 200,
            'severity' => 'warning', 'is_active' => true,
        ]);
        AlarmRule::factory()->create([
            'sensor_id' => null, 'sensor_type' => 'humidity',
            'name' => 'Kelembapan Global Warning', 'operator' => '>', 'threshold_max' => 70,
            'severity' => 'warning', 'is_active' => true,
        ]);
        AlarmRule::factory()->create([
            'sensor_id' => null, 'sensor_type' => 'temperature',
            'name' => 'Suhu Global Kritis', 'operator' => '>=', 'threshold_max' => 35,
            'severity' => 'critical', 'is_active' => true,
        ]);
    }
}
