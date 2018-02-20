<?php

namespace Martin\Tracking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Software extends Model
{
    use SoftDeletes;

    protected $table = 'softwares';

    protected $fillable = [
        'name',
        'description',
        'purchased_at',
        'cancelled_at',
        'purchased_from',
        'license_information',

        'amount_cad',
        'usd_to_cad_rate',
        'amount_usd',

        'billing_cycle',
    ];

    protected $casts = [
        'purchased_at' => 'date:Y-m-d',
        'cancelled_at' => 'date:Y-m-d',
    ];

    /*
     * Mutators
     */

    /**
     * @param $value
     * @return float|int
     */
    public function getAmountUsdAttribute($value) {
        return $value / 100;
    }

    /**
     * @param $value
     */
    public function setAmountUsdAttribute($value) {
        $this->attributes['amount_usd'] = round($value * 100);
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getAmountCadAttribute($value) {
        return $value / 100;
    }

    /**
     * @param $value
     */
    public function setAmountCadAttribute($value) {
        $this->attributes['amount_cad'] = round($value * 100);
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getUsdToCadRateAttribute($value) {
        return $value / 100000;
    }

    /**
     * @param $value
     */
    public function setUsdToCadRateAttribute($value) {
        $this->attributes['usd_to_cad_rate'] = round($value * 100000);
    }
}