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
        Schema::create('trial_balances', function (Blueprint $table) {
            $table->uuid('tb_id')
                ->default(DB::raw('(UUID())'))
                ->primary();
            $table->foreignUuid('report_id')
                ->constrained(table:'financial_reports', column: 'report_id')
                ->cascadeOnDelete();
            $table->enum('tb_type', ['pre','post'])->nullable();
            $table->string('template_name');
            $table->longText('tb_data'); // json

            $table->foreign('template_name')
                ->references('template_name')
                ->on('report_templates')
                ->onDelete('restrict')
                ->default('tb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trial_balances');
    }
};
