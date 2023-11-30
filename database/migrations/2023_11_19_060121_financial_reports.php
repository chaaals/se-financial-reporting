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
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->uuid('report_id')
                ->default(DB::raw('(UUID())'))
                ->primary();
            $table->string('report_name');
            $table->year('fiscal_year');
            $table->enum('interim_period', ['Quarterly', 'Annual']);
            $table->enum('quarter', ['Q1', 'Q2', 'Q3', 'Q4'])->nullable();
            $table->enum('report_status', ['Draft','For Approval', 'Approved'])->default("Draft");
            $table->boolean('approved')->default(false);
            $table->longText('notes')->nullable();
            $table->date('date');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};
