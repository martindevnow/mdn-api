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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project() {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}