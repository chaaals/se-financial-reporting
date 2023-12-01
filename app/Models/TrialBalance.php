<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrialBalance extends Model
{
    protected $primaryKey = 'tb_id';
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'tb_type',
        'tb_data',
        'report_name',
        'report_status',
        'quarter',
        'approved',
        'date',
        'interim_period',
    ];

    protected $casts = [
        'tb_id' => 'string',
    ];

}
