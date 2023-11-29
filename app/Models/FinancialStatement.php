<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialStatement extends Model
{
    protected $primaryKey = 'statement_id';
    use HasFactory;
    protected $fillable = [
        'statement_type',
        'statement_name',
        'tb_id',
        'template_name',
        'fs_data'
    ];
    protected $casts = [
        'statement_id' => 'string',
    ];
}
