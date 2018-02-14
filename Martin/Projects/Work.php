<?php

namespace Martin\Projects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\Billing\Charge;

class Work extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'duration',
        'performed_at',
        'billable',
        'type',
    ];

    protected $casts = [
        'performed_at' => 'date:Y-m-d',
    ];


    /**
     * Turn this into a billable charge
     */
    public function makeCharge(Carbon $billable_as_of_date = null) {
        if ($billable_as_of_date == null)
            $billable_as_of_date = Carbon::now();

        $this->charges()->create([
            'rate'              => $this->getRate(),
            'quantity'          => $this->getQuantity(),
            'total_cost'        => $this->getRate() * $this->getQuantity(),
            'billable_as_of'    => $billable_as_of_date,
        ]);
    }

    /**
     * Get the $ / hour rate for the activity
     *
     * @return float|int|null
     */
    private function getRate() {
        if ($this->type == 'programming')
            return $this->project
                ->contract
                ->programming_hourly_rate;

        if ($this->type == 'sysadmin')
            return $this->project->contract->sysadmin_hourly_rate;

        if ($this->type == 'consulting')
            return $this->project->contract->consulting_hourly_rate;

        // TODO: Add in an exception here
        return null;
    }

    /**
     * Convert the minutes to Hours
     *
     * @return float|int
     */
    private function getQuantity() {
        return $this->duration / 60;
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