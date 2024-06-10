<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class TrialBalanceHistory extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'tb_data_id';
    protected $keyType = 'string';
    public $incrementing = false;
    use HasFactory;
    protected $fillable = [
        'tb_id',
        'tb_data',
        'totals_data',
        // 'date',
        'interim_period',
        'template_name',
    ];

    protected $casts = [
        'tb_data_id' => 'string',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });

        // static::deleting(function ($model) {
        //     $model->tbTotals()->delete();
        // });
    }

    public function trialBalance()
    {
        return $this->belongsTo(TrialBalance::class);
    }

    // public function latestTbTotals()
    // {
    //     return $this->hasOne(TrialBalanceTotals::class, 'tb_data_id')->latestOfMany('created_at');
    // }

}
