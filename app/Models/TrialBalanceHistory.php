<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrialBalanceHistory extends Model
{
    use SoftDeletes;

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

    public function trialBalance()
    {
        return $this->belongsTo(TrialBalance::class);
    }
}
