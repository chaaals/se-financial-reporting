<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialStatementAccountTotals extends Model
{
    protected $primaryKey = 'totals_id';
    use HasFactory;
    protected $fillable = [
        'fs_id',
        'totals_data',
    ];

    protected $casts = [
        'totals_id' => 'string',
    ];
}
