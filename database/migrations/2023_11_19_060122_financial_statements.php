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
            $table->enum('fs_type', ['SFPO', 'SFPE', 'SCF']);
            $table->foreignUuid('report_id')
                ->constrained(table:'financial_reports', column: 'report_id')
                ->cascadeOnDelete();
            $table->string('template_name');
            $table->longText('fs_data'); // json

            $table->foreign('template_name')
                ->references('template_name')
                ->on('report_templates')
                ->onDelete('restrict');
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
