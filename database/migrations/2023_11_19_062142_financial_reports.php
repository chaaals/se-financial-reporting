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
            $table->uuid('report_id')->default(DB::raw('(UUID())'));
            $table->string('report_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('report_type', ['Quarterly', 'Annual']);
            $table->enum('report_status', ['Draft','For Approval', 'Approved']);
            $table->boolean('approved');
            $table->uuid('tb_id');
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
