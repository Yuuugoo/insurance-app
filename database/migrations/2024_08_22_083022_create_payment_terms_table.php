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
        Schema::create('payment_terms', function (Blueprint $table) {
            $table->id('terms_id');
            $table->unsignedBigInteger('report_terms_id')->nullable();
            $table->foreign('report_terms_id')->references('reports_id')->on('reports');
            $table->date('due_date')->nullable();
            $table->float('terms_gross_premium', 10, 2)->nullable();
            $table->float('terms_payments', 10, 2)->nullable();
            $table->float('terms_outstanding_balance', 10, 2)->nullable();
            $table->boolean('is_paid')->default(0)->nullable();
            $table->string('terms_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_terms');
    }
};
