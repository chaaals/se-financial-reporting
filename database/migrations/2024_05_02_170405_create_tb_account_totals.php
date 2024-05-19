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
        Schema::create('trial_balance_totals', function (Blueprint $table) {
            $table->id('totals_id');
            // $table->foreignUuid('tb_data_id')
            //     ->constrained(table: 'trial_balance_histories', column: 'tb_data_id')
            //     ->cascadeOnDelete();
            $table->jsonb('totals_data');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trial_balance_totals');
    }
};
