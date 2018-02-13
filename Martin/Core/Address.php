<?php

namespace Martin\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\Transactions\Order;
use ReflectionClass;

class Address extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'active',
        'name',
        'description',
        'company',

        'street_1', 	// string
        'street_2', 	// string
        'city', 	    // string
        'province',     // string
        'postal_code',  // string
        'country',      // string

        'phone', 	    // string
        'buzzer',

        'addressable_id',
        'addressable_type',
    ];

    protected $type;

    public static function createFromForm($formData) {
        $formData['active'] = 1;
        return Address::create($formData);
    }

    /**
     * Return the Type of Address
     *
     * @return string
     */
    public function getAddressableType() {
        if ($this->type)
            return $this->type;

        return $this->type = strtolower((new ReflectionClass($this->addressable))
            ->getShortName());
    }

    /**
     * Get the URL for the associated Entity
     *
     * @return string
     */
    public function getUrlToAddressable() {
        return "/admin/{$this->getAddressableType()}/{$this->addressable_id}";
    }

    /**
     * TODO: Make this 'smarter'
     *
     * @return int
     */
    public function getTax() {
        return 0;
    }

    public function toString() {
        return $this->street_1 . ', '
            . ($this->street_2 ? $this->street_2 . ', ' : '')
            . $this->city . ', '
            . $this->province . ', '
            . $this->postal_code;
    }

    /**
     * An Address can be attached to Any Entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function addressable() {
        return $this->morphTo();
    }

    /**
     * An address will have many orders attached to the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders() {
        return $this->hasMany(Order::class, 'delivery_address_id');
    }
}
