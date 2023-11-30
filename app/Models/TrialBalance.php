<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrialBalance extends Model
{
    protected $primaryKey = 'tb_id';
    use HasFactory;

    const UPDATED_AT = null;
    protected $fillable = [
        'report_id',
        'tb_type',
        'tb_data',
    ];

    protected $casts = [
        'tb_id' => 'string',
    ];
}
