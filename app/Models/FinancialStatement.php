<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialStatement extends Model
{
    protected $primaryKey = 'fs_id';
    use HasFactory;
    protected $fillable = [
        'collection_id',
        'fs_type',
        'fs_data',
        'template_name',
    ];
}
