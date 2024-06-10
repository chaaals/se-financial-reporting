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
                ->primary();
            $table->enum('tb_type', ['pre', 'post'])->nullable();

            $table->string('tb_name');
            $table->enum('tb_status', ['Draft', 'For Approval', 'Change Requested', 'Approved'])->default('Draft');
            $table->enum('quarter', ['Q1', 'Q2', 'Q3', 'Q4'])->nullable();
            $table->boolean('approved')->default(false);
            // $table->date('tb_date');
            $table->string('tb_month')->nullable();
            $table->string('tb_year');
            $table->enum('interim_period', ['Monthly', 'Quarterly', 'Annual']);
            $table->string('template_name')->default('tb');
            $table->integer('debit_grand_totals');
            $table->integer('credit_grand_totals');
            $table->softDeletes();
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
        Schema::dropIfExists('trial_balances');
    }
};
