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
        Schema::create('report_templates', function (Blueprint $table) {
            $table->string('template_name', 20)
                ->primary();
            $table->longText('template');
        });

        DB::table('report_templates')->insert([
            [
                'template_name' => 'tb',
                'template' => '{"sample": "content"}',
            ],
            [
                'template_name' => 'partial_tb',
                'template' => '{"sample": "content"}',
            ],
            [
                'template_name' => 'sfpo',
                'template' => '{"sample": "content"}',
            ],
            [
                'template_name' => 'sfpe',
                'template' => '{"sample": "content"}',
            ],
            [
                'template_name' => 'scf',
                'template' => '{"sample": "content"}',
            ],
            [
                'template_name' => 'sfpo_vals',
                'template' => '[17,18,19,20,21,23,27,28,32,33,34,35,36,37,46,47,48,49,53,61,62,63,64]',
            ],
            [
                'template_name' => 'sfpe_vals',
                'template' => '[14,15,16,17,22,23,24,25,26,27]',
            ],
            [
                'template_name' => 'scf_vals',
                'template' => '[13,15,16,20,21,29,33,34,35]',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_templates');
    }
};
