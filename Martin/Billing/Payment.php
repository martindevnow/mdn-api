<?php

namespace Martin\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\Clients\Client;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'received_at',
        'cheque_number',
        'amount_cad',
        'amount_usd',
        'usd_to_cad_rate',
        'client_id',
    ];

    protected $casts = [
        'received_at' => 'date:Y-m-d',
    ];

    /**
     * Determine if the payment has been assigned to enough invoices or not
     *
     * @return bool
     */
    public function isFulledAssignedToInvoices() {
        if ($this->getInvoicesPaidTotal('cad') == $this->amount_cad)
            return true;

        if ($this->getInvoicesPaidTotal('usd') == $this->amount_usd)
            return true;

        return false;
    }

    /**
     * Get the total of the invoices associated
     *
     * @param string $type
     * @return mixed
     */
    public function getInvoicesPaidTotal($type = "cad") {
        if ($type == "cad")
            return $this->invoices->sum('amount_cad');

        return $this->invoices->sum('amount_usd');
    }


    /*
     * Mutators
     */

    /**
     * @param $value
     * @return float|int
     */
    public function getAmountUsdAttribute($value) {
        return $value / 100;
    }

    /**
     * @param $value
     */
    public function setAmountUsdAttribute($value) {
        $this->attributes['amount_usd'] = round($value * 100);
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getAmountCadAttribute($value) {
        return $value / 100;
    }

    /**
     * @param $value
     */
    public function setAmountCadAttribute($value) {
        $this->attributes['amount_cad'] = round($value * 100);
    }

    /**
     * @param $value
     * @return float|int
     */
    public function getUsdToCadRateAttribute($value) {
        return $value / 100000;
    }

    /**
     * @param $value
     */
    public function setUsdToCadRateAttribute($value) {
        $this->attributes['usd_to_cad_rate'] = round($value * 100000);
    }


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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function invoices() {
        return $this->belongsToMany(Invoice::class);
    }
}