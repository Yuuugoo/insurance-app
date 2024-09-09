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
            $table->string('1st_arpr_num')->nullable();
            $table->string('2nd_arpr_num')->nullable();
            $table->string('3rd_arpr_num')->nullable();
            $table->string('4th_arpr_num')->nullable();
            $table->string('5th_arpr_num')->nullable();
            $table->string('6th_arpr_num')->nullable();
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
