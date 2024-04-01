<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrialBalanceHistory extends Model
{
    protected $primaryKey = 'tb_data_id';
    use HasFactory;
    protected $fillable = [
        'tb_id',
        'tb_data',
        'date',
        'interim_period',
        'template_name',
    ];

    protected $casts = [
        'tb_data_id' => 'string',
    ];
}
