<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrialBalance extends Model
{
    use HasFactory;
    protected $primaryKey = 'tb_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'tb_type',
        'tb_data',
        'tb_name',
        'tb_status',
        'quarter',
        'approved',
        'tb_date',
        'interim_period',
        'template_name',
    ];

    protected $casts = [
        'tb_id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }

    public function tbData(){
        return $this->hasMany(TrialBalanceHistory::class, 'tb_id');
    }

    public function latestTbData(){
        return $this->hasOne(TrialBalanceHistory::class, 'tb_id')->latestOfMany('created_at');
    }
}
