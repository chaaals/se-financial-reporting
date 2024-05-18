<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrialBalanceTotals extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'totals_id';
    use HasFactory;
    protected $fillable = [
        'tb_id',
        'totals_data',
    ];

    protected $casts = [
        'totals_id' => 'string',
    ];

    public function trialBalance()
    {
        return $this->belongsTo(TrialBalance::class);
    }
}
