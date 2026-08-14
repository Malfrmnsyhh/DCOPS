<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dc_port_connections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('from_port_id')->unsigned();   // FK ke dc_device_ports
            $table->bigInteger('to_port_id')->unsigned();     // FK ke dc_device_ports
            $table->enum('cable_type', ['cat5', 'cat6', 'fiber', 'coax']);
            $table->string('cable_label')->nullable();
            $table->decimal('length_m', 6, 2)->nullable();
            $table->timestamps();

            $table->foreign('from_port_id')
                  ->references('id')
                  ->on('dc_device_ports')
                  ->cascadeOnDelete();

            $table->foreign('to_port_id')
                  ->references('id')
                  ->on('dc_device_ports')
                  ->cascadeOnDelete();

            $table->unique('from_port_id');
            $table->unique('to_port_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dc_port_connections');
    }
};
