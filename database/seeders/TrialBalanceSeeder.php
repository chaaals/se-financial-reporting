<?php

namespace Database\Seeders;

use App\Models\TrialBalance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrialBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TrialBalance::factory(5)->create([
            'tb_data' => '{ data: sample data }',
            'report_name' => 'Test TB report',
            'report_status' => 'Approved',
            'date' => date('Y-m-d'),
            'interim_period' => 'Annual',
            'template_name' => 'tb'
        ]);
    }
}
