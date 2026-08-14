<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dc_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('site_id')->unsigned();   
            $table->string('code');                      
            $table->string('name');
            $table->string('floor')->nullable();
            $table->decimal('area_sqm', 8, 2)->nullable();
            $table->enum('status', ['active', 'decommissioned']);
            $table->timestamps();

            $table->foreign('site_id')
                  ->references('id')
                  ->on('dc_sites')
                  ->cascadeOnDelete();

            $table->unique(['site_id', 'code']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('dc_rooms');
    }
};
