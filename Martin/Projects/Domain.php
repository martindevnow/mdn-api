<?php

namespace Martin\Projects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'registrar',
        'originally_registered_at',
        'expires_at',
        'project_id',
    ];

    protected $casts = [
        'originally_registered_at' => 'date:Y-m-d',
        'expires_at' => 'date:Y-m-d',
    ];

    public function getOriginallyRegisteredAtAttribute($val) {
        return $this->removeTime($val);
    }

    public function getExpiresAtAttribute($val) {
        return $this->removeTime($val);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }
}