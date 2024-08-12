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
        Schema::create('reports', function (Blueprint $table) {
            $table->id('reports_id');
            $table->unsignedBigInteger('submitted_by_id')->nullable();
            $table->foreign('submitted_by_id')->references('id')->on('users');
            $table->text('arpr_num');
            $table->date('arpr_date');
            $table->date('inception_date')->nullable();
            $table->text('assured');
            $table->text('policy_num');
            $table->string('application', 50);
            $table->string('cashier_remarks')->nullable();
            $table->string('acct_remarks')->nullable();
            $table->text('policy_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
