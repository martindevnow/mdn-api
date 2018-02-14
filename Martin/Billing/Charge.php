<?php

namespace Martin\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\Projects\Project;

class Charge extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'invoice_id',
        'chargeable_id',

        'chargeable_type',
        'rate',
        'quantity',
        'total_cost',
        'billable_as_of',
        'billed_at',
    ];

    protected $casts = [
        'billable_as_of' => 'date:Y-m-d',
        'billed_at' => 'date:Y-m-d',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project() {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function chargeable() {
        return $this->morphTo();
    }
}