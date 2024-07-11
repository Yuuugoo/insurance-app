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
            $table->id();
            $table->string('cost_center', 50);
            $table->string('arpr_num', 50);
            $table->date('arpr_date');
            $table->date('inception_date')->nullable();
            $table->string('assured', 50);
            $table->string('policy_num', 50);
            $table->string('insurance_prod', 10);
            $table->string('application', 50);
            $table->string('cashier_remarks')->nullable();
            $table->date('remit_date')->nullable();
            $table->string('acct_remarks')->nullable();
            $table->string('depo_slip')->nullable();
            $table->string('policy_file')->nullable();
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
