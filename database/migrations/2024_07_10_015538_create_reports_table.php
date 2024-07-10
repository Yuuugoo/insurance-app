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
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('report_payments');
            $table->string('sale_person', 50)->nullable();
            $table->string('cost_center', 50);
            $table->string('arpr_num', 50);
            $table->date('arpr_date');
            $table->date('inception_date')->nullable();
            $table->string('assured', 50)->nullable();
            $table->string('policy_num', 50)->nullable();
            $table->string('insurance_prod', 10)->nullable();
            $table->string('application', 50)->nullable();
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
