<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialStatementAccountTotals extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'totals_id';
    use HasFactory;
    protected $fillable = [
        'fs_id',
        'totals_data',
    ];

    protected $casts = [
        'totals_id' => 'string',
    ];

    public function financialStatement()
    {
        return $this->belongsTo(FinancialStatement::class, 'fs_id');
    }
}
