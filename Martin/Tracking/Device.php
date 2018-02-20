<?php

namespace Martin\Tracking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'purchased_at',
        'cost',
        'notes',
    ];

    protected $casts = [
        'purchased_at'  => 'date:Y-m-d'
    ];


    /*
     * Mutators
     */

    /**
     * @param $value
     * @return float|int
     */
    public function getCostAttribute($value) {
        return $value / 100;
    }

    /**
     * @param $value
     */
    public function setCostAttribute($value) {
        $this->attributes['cost'] = round($value * 100);
    }

}