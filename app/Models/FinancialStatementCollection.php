<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialStatementCollection extends Model
{
    protected $primaryKey = 'collection_id';
    use HasFactory;
    protected $fillable = [
        'fs_type',
        'fs_data',
        'collection_name',
        'collection_status',
        'quarter',
        'approved',
        'date',
        'interim_period',
        'tb_id',
        'template_name',
    ];
    protected $casts = [
        'statement_id' => 'string',
    ];
}
