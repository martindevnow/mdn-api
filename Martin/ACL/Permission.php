<?php

namespace Martin\ACL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    /**
     * Fields which may be mass-assigned
     *
     * @var array
     */
    protected $fillable = [
        'label', 'code', 'description',
    ];

    /**
     * Assign $this Permission to a Role
     *
     * @param Role $role
     */
    public function assignToRole(Role $role) {
        $this->roles()->attach($role->id);
    }

    /**
     * Assign $this Permission to a Role
     *
     * @param Role $role
     */
    public function removeFromRole(Role $role) {
        $this->roles()->detach($role->id);
    }

    /**
     * Many various Permissions belongToMany various Roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany(Role::class);
    }
}
