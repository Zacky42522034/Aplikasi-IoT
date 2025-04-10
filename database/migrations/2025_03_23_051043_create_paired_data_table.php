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
        Schema::create('paired_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId("paired_device_id")->constrained('paired_devices')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string("data1")->nullable();
            $table->string("data2")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paired_data');
    }
};
