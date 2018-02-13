<?php

namespace Martin\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\ACL\User;
use ReflectionClass;

class Image extends Model {

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uploader_id',
        'content',
        'height',
        'width',
        'extension',
        'name',
        'imageable_id',
        'imageable_type',
    ];

    /**
     * @return mixed|string
     */
    public function type() {
        return strtolower((new ReflectionClass($this->imageable))->getShortName());
    }

    /**
     * @return string
     */
    public function url() {
        return "/images/{$this->type()}/{$this->imageable_id}-{$this->id}.{$this->extension}";
    }

    /**
     * An Image can be added to Anything
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function imageable() {
        return $this->morphTo();
    }

    /**
     * Images are added by Users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploader() {
        return $this->belongsTo(User::class, 'uploader_id');
    }
} 