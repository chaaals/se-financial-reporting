<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    protected $primaryKey = 'report_id';
    use HasFactory;

    protected $fillable = [
        'report_name',
        'fiscal_year',
        'interim_period',
        'quarter',
        'report_status',
        'approved',
        'tb_id'
    ];

    protected $casts = [
        'report_id' => 'string',
    ];
}
