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
        Schema::create('paired_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('device_id')->unique(10);
            $table->string('category');
            $table->string('lokasi');
            $table->string('IP_Address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paired_devices');
    }
};
