<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dc_racks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('room_id')->unsigned(); 
            $table->string('code');                      
            $table->string('name');
            $table->integer('u_height');                 
            $table->decimal('max_power_kw', 8, 2)->nullable();
            $table->enum('status', ['active', 'decommissioned']);
            $table->timestamps();

            $table->foreign('room_id')
                  ->references('id')
                  ->on('dc_rooms')
                  ->cascadeOnDelete();

            $table->unique(['room_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dc_racks');
    }
};
