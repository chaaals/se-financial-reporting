<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrialBalance extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    protected $fillable = [
        'tb_name',
        'period',
        'tb_data',
    ];
}
