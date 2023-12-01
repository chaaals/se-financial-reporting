<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialStatement extends Model
{
    protected $primaryKey = 'statement_id';
    use HasFactory;
    protected $fillable = [
        'fs_type',
        'fs_data',
        'report_name',
        'report_status',
        'quarter',
        'approved',
        'date',
        'interim_period',
        'template_name',
    ];
    protected $casts = [
        'statement_id' => 'string',
    ];
}
