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
        Schema::create('financial_statements', function (Blueprint $table) {
            $table->uuid('statement_id')
                ->default(DB::raw('(UUID())'))
                ->primary();
            $table->foreignUuid('tb_id')
                ->constrained(table:'trial_balances', column: 'tb_id')
                ->cascadeOnDelete();
            $table->enum('statement_type', ['SFPO', 'SFPE', 'SCNAE', 'SCF', 'SCBAA']);
            $table->longText('fs_data'); // json
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_statements');
    }
};
