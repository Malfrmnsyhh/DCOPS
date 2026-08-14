<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dc_device_ports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('device_id')->unsigned();
            $table->string('name');                   
            $table->enum('type', ['ethernet', 'fiber', 'power', 'other']);
            $table->integer('speed_mbps')->nullable();
            $table->string('mac_address')->nullable();
            $table->enum('status', ['active', 'disabled']);
            $table->timestamps();

            $table->foreign('device_id')
                  ->references('id')
                  ->on('dc_devices')
                  ->cascadeOnDelete();

            $table->unique(['device_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dc_device_ports');
    }
};
