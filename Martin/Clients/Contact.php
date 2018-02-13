<?php

namespace Martin\Clients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'client_id',
    ];

    public function client() {
        return $this->belongsTo(Client::class);
    }
}