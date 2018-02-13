<?php

namespace Martin\Clients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\Billing\Charge;
use Martin\Projects\Project;

class Service extends Model
{
    use SoftDeletes;

    /**
     * Mass-assignable fields
     *
     * @var array
     */
    protected $fillable = [
        'project_id',

        'description',
        'cost',
        'billing_frequency',
        'activated_at',
        'deactivated_at',
        'valid_from_date',
        'valid_until_date',
    ];

    /**
     * Fields cast as Carbon/Carbon
     *
     * @var array
     */
    protected $casts = [
        'activated_at' => 'date:Y-m-d',
        'deactivated_at' => 'date:Y-m-d',
        'valid_from_date' => 'date:Y-m-d',
        'valid_until_date' => 'date:Y-m-d',
    ];


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