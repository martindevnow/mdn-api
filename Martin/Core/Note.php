<?php

namespace Martin\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\ACL\User;
use Spatie\Activitylog\Traits\LogsActivity;

class Note extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'author_id',
        'content',
        'noteable_id',
        'noteable_type'
    ];

    /**
     * A Note can be attached to Anything
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function noteable() {
        return $this->morphTo();
    }

    /**
     * A Note is created by a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }
}
