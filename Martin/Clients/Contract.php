<?php

namespace Martin\Clients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\Billing\Invoice;
use Martin\Projects\Project;

class Contract extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
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

    /**
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices() {
        return $this->hasMany(Invoice::class);
    }
}