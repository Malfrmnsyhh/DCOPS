<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dc_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ticket_number')->unique();    // di‑generate oleh observer
            $table->bigInteger('alarm_id')->unsigned()->nullable(); // FK ke dc_alarms
            $table->bigInteger('rack_id')->unsigned()->nullable(); // FK ke dc_racks
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', ['hardware', 'software', 'network', 'other']);
            $table->enum('priority', ['low', 'medium', 'high', 'critical']);
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed']);
            $table->bigInteger('created_by')->unsigned(); // FK ke users
            $table->bigInteger('assigned_to')->unsigned()->nullable(); // FK ke users
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('alarm_id')
                  ->references('id')
                  ->on('dc_alarms')
                  ->nullOnDelete();

            $table->foreign('rack_id')
                  ->references('id')
                  ->on('dc_racks')
                  ->nullOnDelete();

            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();

            $table->foreign('assigned_to')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dc_tickets');
    }
};
