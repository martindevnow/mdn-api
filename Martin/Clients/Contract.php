<?php

namespace Martin\Clients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\Billing\Invoice;
use Martin\Projects\Project;

class Contract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'programming_hourly_rate',
        'sysadmin_hourly_rate',
        'consulting_hourly_rate',
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
    public function getProgrammingHourlyRateAttribute($value) {
        return $value / 100;
    }

    /**
     * @param $value
     */
    public function setProgrammingHourlyRateAttribute($value) {
        $this->attributes['programming_hourly_rate'] = round($value * 100);
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getSysadminHourlyRateAttribute($value) {
        return $value / 100;
    }

    /**
     * @param $value
     */
    public function setSysadminHourlyRateAttribute($value) {
        $this->attributes['sysadmin_hourly_rate'] = round($value * 100);
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getConsultingHourlyRateAttribute($value) {
        return $value / 100;
    }

    /**
     * @param $value
     */
    public function setConsultingHourlyRateAttribute($value) {
        $this->attributes['consulting_hourly_rate'] = round($value * 100);
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices() {
        return $this->hasMany(Invoice::class);
    }
}