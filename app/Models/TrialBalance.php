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
        'report_id',
        'tb_type',
        'tb_data',
    ];

    protected $casts = [
        'tb_id' => 'string',
    ];

    public function financialReport()
    {
        return $this->belongsTo(FinancialReport::class, 'report_id', 'report_id');
    }
}
