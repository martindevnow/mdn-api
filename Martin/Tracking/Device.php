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

}