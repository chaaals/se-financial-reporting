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
        Schema::create('trial_balance_histories', function (Blueprint $table) {
            $table->uuid('tb_data_id')
                ->default(DB::raw('(UUID())'))
                ->primary();
            $table->foreignUuid('tb_id')
                ->constrained(table: 'trial_balances', column: 'tb_id')
                ->cascadeOnDelete();
            $table->longText('tb_data'); // json
            $table->date('date');
            $table->softDeletes();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_data_history');
    }
};

