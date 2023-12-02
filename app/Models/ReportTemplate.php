<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTemplate extends Model
{
    use HasFactory;
    protected $primaryKey = 'template_name';

    protected $casts = [
        'template_name' => 'string',
    ];
}
