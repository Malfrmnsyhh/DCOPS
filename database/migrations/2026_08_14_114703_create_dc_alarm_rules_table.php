<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dc_alarm_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sensor_id')->unsigned()->nullable(); // null = rule global per tipe
            $table->enum('sensor_type', ['temperature', 'humidity', 'power', 'airflow', 'other'])->nullable();
            $table->string('name');
            $table->enum('operator', ['>', '<', '>=', '<=']);
            $table->decimal('threshold_min', 12, 4)->nullable();
            $table->decimal('threshold_max', 12, 4)->nullable();
            $table->enum('severity', ['warning', 'critical']);
            $table->boolean('auto_create_ticket')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('sensor_id')
                  ->references('id')
                  ->on('dc_sensors')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dc_alarm_rules');
    }
};
