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
        Schema::create('financial_statement_collections', function (Blueprint $table) {
            $table->uuid('collection_id')
                ->default(DB::raw('(UUID())'))
                ->primary();
            $table->string('collection_name');
            $table->enum('collection_status', ['Draft','For Approval', 'Change Requested', 'Approved'])->default('Draft');
            $table->enum('quarter', ['Q1', 'Q2', 'Q3', 'Q4'])->nullable();
            $table->boolean('approved')->default(false);
            $table->date('date');
            $table->enum('interim_period', ['Quarterly', 'Annual']);
            $table->foreignUuid('tb_id')
                ->constrained(table: 'trial_balances', column: 'tb_id')
                ->cascadeOnDelete();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_statement_collections');
    }
};
