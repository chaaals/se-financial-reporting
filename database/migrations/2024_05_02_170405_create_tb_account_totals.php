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
        Schema::create('tb_account_totals', function (Blueprint $table) {
            $table->id('totals_id');
            $table->foreignUuid('tb_id')
                ->references('tb_id')
                ->on('trial_balances');
            $table->jsonb('totals_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_account_totals');
    }
};
