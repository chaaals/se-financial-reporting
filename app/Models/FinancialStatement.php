<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialStatement extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'fs_id';
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [
        'collection_id',
        'fs_type',
        'fs_data',
        'template_name',
    ];

    protected $casts = [
        'fs_id' => 'string',
    ];
}
