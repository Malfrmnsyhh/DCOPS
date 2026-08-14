<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dc_sensor_readings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sensor_id')->unsigned();   // FK ke dc_sensors
            $table->decimal('value', 12, 4);
            $table->timestamp('recorded_at');
            // No timestamps (created_at/updated_at) – set $timestamps = false di model
            $table->foreign('sensor_id')
                  ->references('id')
                  ->on('dc_sensors')
                  ->cascadeOnDelete();

            $table->unique(['sensor_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dc_sensor_readings');
    }
};
