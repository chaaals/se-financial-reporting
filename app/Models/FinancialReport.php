<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FinancialReport extends Model
{
    use HasFactory;
    protected $primaryKey = 'report_id';
    public $incrementing = false;

    protected static function booted()
    {
        /**
         * allow report_id to be read in creation so it can be used
         * as a foreign key
         */ 
        static::creating(function ($model) {
            $model->report_id = Str::uuid()->toString();
        });
    }

    protected $fillable = [
        'report_name',
        'fiscal_year',
        'interim_period',
        'quarter',
        'report_status',
        'approved',
        'date',
        'notes',
    ];

    protected $casts = [
        'report_id' => 'string',
    ];
}
