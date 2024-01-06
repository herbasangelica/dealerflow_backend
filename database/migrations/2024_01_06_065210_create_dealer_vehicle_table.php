<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dealer_vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manufacturer_vehicle_id');
            $table->foreign('manufacturer_vehicle_id')->references('id')->on('manufacturer_vehicles')->onDelete('cascade');
            $table->string('status');
            $table->string('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_vehicle');
    }
};
