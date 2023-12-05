<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class FinancialStatementCollection extends Model
{
    protected $primaryKey = 'collection_id';
    public $incrementing = false;

    use HasFactory;
    protected $fillable = [
        'collection_name',
        'collection_status',
        'quarter',
        'approved',
        'date',
        'interim_period',
        'tb_id',
        'template_name',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->collection_id)) {
                $model->collection_id = Str::uuid()->toString();
            }
        });
    }
}
