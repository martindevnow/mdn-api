<?php

namespace Martin\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Filesystem\Filesystem;
use Martin\ACL\User;

class Attachment extends Model
{
    use SoftDeletes;

    /**
     * Mass-assignable Fields
     *
     * @var array
     */
    protected $fillable = [
        'uploader_id',
        'original_filename',
        'filename',
        'extension',
        'attachmentable_id',
        'attachmentable_type',
    ];

//    /**
//     * Returns a download response to the file location
//     *
//     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
//     */
//    public function download() {
//        return response()->download($this->fullpath(), $this->original());
//    }
//
//    /**
//     * @return string
//     */
//    public function fullpath() {
//        return base_path() . '/' . $this->filename . '.' . $this->extension;
//    }
//
//    /**
//     * @return string
//     */
//    public function original() {
//        return $this->original_filename . '.' . $this->extension;
//    }

    /**
     * This can be an attachment to anything
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachmentable() {
        return $this->morphTo();
    }

    /**
     * Attachments can only be uploaded by admins
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploader() {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
