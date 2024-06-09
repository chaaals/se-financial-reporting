<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportNote extends Model
{
    use HasFactory;
    protected $primaryKey = 'note_id';

    protected $fillable = [
        'tb_id',
        'collection_id',
        'participants',
        'content',
        'author',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'note_id' => 'string'
    ];
}
