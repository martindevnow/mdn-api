<?php

namespace Martin\ACL;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Martin\ACL\Traits\HasRoles;
use Martin\Core\Traits\CoreRelations;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    // Required for Notes, Attachments, etc..
    use CoreRelations;

    // Required for ACL
    use HasRoles;

    // Required for Laravel/Passport
    use HasApiTokens;

    // Required for Logging changes to Users
    use LogsActivity;
    static $logFillable = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'stripe_active',
        'subscription_end_at',
        'stripe_customer_id',
    ];

    /**
     * @var array
     */
    protected $guarded = [
        'stripe_customer_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @param $id
     * @return mixed
     */
    public function getPetById($id) {
        return $this->pets()->where('id', $id)->firstOrFail();
    }

    /**
     * Return a simple list of the pets
     *
     * @return string
     */
    public function getPets() {
        if (! $this->pets()->count())
            return '';

        return implode(
            ', ',
            $this->pets->pluck('name')->toArray()
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pets() {
        return $this->hasMany(Pet::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plans() {
        return $this->hasMany(Plan::class, 'customer_id');
    }
}
