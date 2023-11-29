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
            $table->string('template_name')
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
