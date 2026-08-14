<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dc_devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('rack_id')->unsigned()->nullable();   
            $table->bigInteger('device_type_id')->unsigned();  
            $table->string('hostname')->unique();
            $table->string('serial_number')->unique();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->integer('position_u')->nullable();               
            $table->integer('u_size')->default(1);                   
            $table->integer('power_watt')->nullable();
            $table->enum('status', ['active', 'standby', 'decommissioned']);
            $table->date('installed_at')->nullable();
            $table->softDeletes();                                   
            $table->timestamps();

            $table->foreign('rack_id')
                  ->references('id')
                  ->on('dc_racks')
                  ->nullOnDelete(); 

            $table->foreign('device_type_id')
                  ->references('id')
                  ->on('dc_device_types')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dc_devices');
    }
};
