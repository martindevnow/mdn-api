<?php

namespace Martin\Clients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\Billing\Charge;
use Martin\Projects\Project;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',

        'description',
        'rate',
        'billing_frequency',

        'activated_at',
        'deactivated_at',
        'valid_from_date',
        'valid_until_date',
    ];

    protected $casts = [
        'activated_at'      => 'date:Y-m-d',
        'deactivated_at'    => 'date:Y-m-d',
        'valid_from_date'   => 'date:Y-m-d',
        'valid_until_date'  => 'date:Y-m-d',
    ];


    /*
     * Mutators
     */

    /**
     * @param $value
     * @return float|int
     */
    public function getRateAttribute($value) {
        return $value / 100;
    }

    /**
     * @param $value
     */
    public function setRateAttribute($value) {
        $this->attributes['rate'] = round($value * 100);
    }

    /**
     * Relationships
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project() {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function charges() {
        return $this->morphMany(Charge::class, 'chargeable');
    }

}