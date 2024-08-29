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
        Schema::table('reports', function (Blueprint $table) {
            $table->string('1st_terms_status')->default('pending');
            $table->string('2nd_terms_status')->default('pending');
            $table->string('3rd_terms_status')->default('pending');
            $table->string('4th_terms_status')->default('pending');
            $table->string('5th_terms_status')->default('pending');
            $table->string('6th_terms_status')->default('pending');

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
