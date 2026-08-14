<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dc_ticket_device', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ticket_id')->unsigned();   // FK ke dc_tickets
            $table->bigInteger('device_id')->unsigned();   // FK ke dc_devices
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('ticket_id')
                  ->references('id')
                  ->on('dc_tickets')
                  ->cascadeOnDelete();

            $table->foreign('device_id')
                  ->references('id')
                  ->on('dc_devices')
                  ->cascadeOnDelete();

            $table->unique(['ticket_id', 'device_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dc_ticket_device');
    }
};
