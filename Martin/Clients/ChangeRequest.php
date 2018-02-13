<?php

namespace Martin\Clients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\ACL\User;
use Martin\Projects\Project;

class ChangeRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description',
        'requested_at',
        'fulfilled_at',
        'project_id',
        'user_id',
    ];

    protected $casts = [
        'fulfilled_at' => 'date:Y-m-d',
        'requested_at' => 'date:Y-m-d',
    ];

    public function getRequestedAtAttribute($val) {
        return $this->removeTime($val);
    }

    public function getFulfilledAtAttribute($val) {
        return $this->removeTime($val);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}