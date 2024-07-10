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
        Schema::create('report_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_num', 50);
            $table->string('car_details')->nullable();
            $table->string('policy_status', 50);
            $table->string('financing_bank', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_vehicles');
    }
};
