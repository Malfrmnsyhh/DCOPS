<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dc_sensors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('rack_id')->unsigned();      // FK ke dc_racks
            $table->string('code')->unique();               // kode unik sensor
            $table->string('name');
            $table->enum('type', ['temperature', 'humidity', 'power', 'airflow', 'other']);
            $table->string('unit');                         // misal °C, %RH, W
            $table->enum('status', ['active', 'inactive']);
            $table->timestamp('last_reading_at')->nullable();
            $table->timestamps();

            $table->foreign('rack_id')
                  ->references('id')
                  ->on('dc_racks')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dc_sensors');
    }
};
