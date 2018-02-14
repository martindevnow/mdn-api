<?php

namespace Martin\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Filesystem\Filesystem;
use Martin\ACL\User;

class Attachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uploader_id',
        'original_filename',
        'filename',
        'extension',
        'attachmentable_id',
        'attachmentable_type',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachmentable() {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploader() {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
