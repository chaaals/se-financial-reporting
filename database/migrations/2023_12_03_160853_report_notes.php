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
        Schema::create('report_notes', function (Blueprint $table) {
            $table->id('note_id')
                ->default(DB::raw('(UUID())'))
                ->primary();
            $table->foreignUuid('tb_id')
                ->nullable()
                ->constrained(table: 'trial_balances', column: 'tb_id')
                ->cascadeOnDelete();
            $table->foreignUuid('collection_id')
                ->nullable()
                ->constrained(table: 'financial_statement_collections', column: 'collection_id')
                ->cascadeOnDelete();
            $table->string('content');
            $table->string('author');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_notes');
    }
};
