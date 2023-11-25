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
            $table->string('tb_name');
            $table->boolean('closing');
            $table->date('period');
            $table->longText('tb_data'); // json
            $table->timestamp('created_at');
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
