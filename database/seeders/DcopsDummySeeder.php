<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DevicePort;
use App\Models\DeviceType;
use App\Models\PortConnection;
use App\Models\Rack;
use App\Models\Room;
use App\Models\Site;
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
        Rack::factory()->create(['room_id' => $roomA->id, 'code' => 'RCK-002', 'name' => 'Rack 02']);
        Rack::factory()->create(['room_id' => $roomB->id, 'code' => 'RCK-001', 'name' => 'Rack 01']);

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
    }
}
