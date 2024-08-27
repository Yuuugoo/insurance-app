<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->float('1st_payment', 15, 2)->nullable();
            $table->float('2nd_payment',15, 2)->nullable();
            $table->float('3rd_payment', 15, 2)->nullable();
            $table->float('4th_payment', 15, 2)->nullable();
            $table->float('5th_payment', 15, 2)->nullable();
            $table->float('6th_payment', 15, 2)->nullable();
            $table->date('1st_payment_date')->nullable();
            $table->date('2nd_payment_date')->nullable();
            $table->date('3rd_payment_date')->nullable();
            $table->date('4th_payment_date')->nullable();
            $table->date('5th_payment_date')->nullable();
            $table->date('6th_payment_date')->nullable();
            $table->boolean('1st_is_paid')->default(0)->nullable();
            $table->boolean('2nd_is_paid')->default(0)->nullable();
            $table->boolean('3rd_is_paid')->default(0)->nullable();
            $table->boolean('4th_is_paid')->default(0)->nullable();
            $table->boolean('5th_is_paid')->default(0)->nullable();
            $table->boolean('6th_is_paid')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            //
        });
    }
};
