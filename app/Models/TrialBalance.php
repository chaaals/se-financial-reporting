<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrialBalance extends Model
{
    protected $primaryKey = 'tb_id';
    use HasFactory;
    protected $fillable = [
        'tb_type',
        'tb_data',
        'tb_name',
        'tb_status',
        'quarter',
        'approved',
        'date',
        'interim_period',
        'template_name',
    ];

    protected $casts = [
        'tb_id' => 'string',
    ];

}
