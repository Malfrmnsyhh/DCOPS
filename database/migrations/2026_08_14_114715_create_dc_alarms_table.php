<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dc_alarms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('alarm_rule_id')->unsigned();   // FK ke dc_alarm_rules
            $table->bigInteger('sensor_id')->unsigned();       // FK ke dc_sensors
            $table->decimal('triggered_value', 12, 4);
            $table->enum('severity', ['warning', 'critical']);
            $table->enum('status', ['open', 'acknowledged', 'resolved']);
            $table->timestamp('triggered_at');
            $table->bigInteger('acknowledged_by')->unsigned()->nullable(); // FK ke users
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('alarm_rule_id')
                  ->references('id')
                  ->on('dc_alarm_rules')
                  ->cascadeOnDelete();

            $table->foreign('sensor_id')
                  ->references('id')
                  ->on('dc_sensors')
                  ->cascadeOnDelete();

            $table->foreign('acknowledged_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dc_alarms');
    }
};
