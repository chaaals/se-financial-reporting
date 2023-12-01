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
            $table->longText('fs_data'); // json
            $table->string('report_name');
            $table->enum('report_status', ['Draft','For Approval', 'Approved'])->default('Draft');
            $table->enum('quarter', ['Q1', 'Q2', 'Q3', 'Q4'])->nullable();
            $table->boolean('approved')->default(false);
            $table->date('date');
            $table->enum('interim_period', ['Quarterly', 'Annual']);
            $table->longText('notes')->nullable();
            $table->string('template_name');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');

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
