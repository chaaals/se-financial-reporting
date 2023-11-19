<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_name',
        'start_date',
        'end_date',
        'report_type',
        'report_status',
        'approved',
        'tb_id'
    ];
}
