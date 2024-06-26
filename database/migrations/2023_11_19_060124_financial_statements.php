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
            $table->id('fs_id');
            $table->foreignUuid('collection_id')
                ->constrained(table: 'financial_statement_collections', column: 'collection_id')
                ->cascadeOnDelete();
            $table->enum('fs_type', ['SFPO', 'SFPE', 'SCF', 'SCNAE', 'SCBAA']);
            $table->longText('fs_data'); // json
            $table->longText('totals_data'); // json
            $table->string('template_name');
            $table->softDeletes();

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
