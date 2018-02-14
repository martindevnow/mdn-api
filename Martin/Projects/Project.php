<?php

namespace Martin\Projects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\Billing\Invoice;
use Martin\Clients\ChangeRequest;
use Martin\Clients\Client;
use Martin\Clients\Contract;
use Martin\Clients\Service;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',

        'client_id',

        'started_at',

        'git_repo_url',
        'production_url',
        'development_url',
    ];

    protected $casts = [
        'started_at' => 'date:Y-m-d',
    ];


    /**
     * Relationships
     */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client() {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices() {
        return $this->hasMany(Invoice::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function changeRequest() {
        return $this->hasMany(ChangeRequest::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server() {
        return $this->belongsTo(Server::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services() {
        return $this->hasMany(Service::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracts() {
        return $this->hasMany(Contract::class);
    }
}