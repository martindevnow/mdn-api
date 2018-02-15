<?php

namespace Martin\Projects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'host',
        'os',
        'username',
        'email',
        'purchased_at',
        'expires_at',
        'cost_monthly',
        'currency',
        'billing_cycle',
    ];

    protected $casts = [
        'purchased_at'  => 'date:Y-m-d',
        'expires_at'    => 'date:Y-m-d',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects() {
        return $this->hasMany(Project::class);
    }
}