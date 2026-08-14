<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dc_ip_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('subnet_id')->unsigned();      // FK ke dc_subnets
            $table->bigInteger('device_port_id')->unsigned()->nullable(); // optional FK ke dc_device_ports
            $table->string('address')->unique();              // IP address
            $table->enum('status', ['available', 'assigned', 'reserved']);
            $table->string('hostname')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            $table->foreign('subnet_id')
                  ->references('id')
                  ->on('dc_subnets')
                  ->cascadeOnDelete();

            $table->foreign('device_port_id')
                  ->references('id')
                  ->on('dc_device_ports')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dc_ip_addresses');
    }
};
