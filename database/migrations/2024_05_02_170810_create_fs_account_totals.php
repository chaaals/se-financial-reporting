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
        Schema::create('fs_account_totals', function (Blueprint $table) {
            $table->id('totals_id');
            $table->foreignUuid('fs_id')
                ->references('fs_id')
                ->on('financial_statements');
            $table->jsonb('totals_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fs_account_totals');
    }
};
