<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'statement_type',
        'statement_name',
        'tb_id',
        'fs_data'
    ];
}
