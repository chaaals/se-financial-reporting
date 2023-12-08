<?php

namespace Database\Seeders;

use App\Models\FinancialStatementCollection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinancialStatementCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FinancialStatementCollection::factory(1)->create([
            "collection_name" => "ngi test fs collection",
            "collection_status" => "Draft",
            "date" => date("Y-m-d"),
            "interim_period" => "Annual",
            "tb_id" => "9af6992b-9443-11ee-a4e7-00ffccf551ba"
        ]);
    }
}
