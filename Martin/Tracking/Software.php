<?php

namespace Martin\Tracking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Software extends Model
{
    use SoftDeletes;

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
}