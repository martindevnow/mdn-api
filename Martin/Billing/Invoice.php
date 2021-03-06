<?php

namespace Martin\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Martin\Clients\Client;
use Martin\Clients\Contract;
use Martin\Projects\Project;
use Martin\Projects\Work;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'amount_usd',
        'usd_to_cad_rate',
        'amount_cad',

        'generated_at',
        'sent_at',
        'paid_at',

        'project_id',
        'client_id',

        'invoice_no',
    ];

    protected $dates = [
        'generated_at'  => 'date:Y-m-d',
        'sent_at'       => 'date:Y-m-d',
        'paid_at'       => 'date:Y-m-d'
    ];

    /**
     * Determine if the invoice has received full payment or not
     *
     * @return bool
     */
    public function isFulledAssignedToInvoices() {
        if ($this->getPaymentsReceivedTotal('cad') == $this->amount_cad)
            return true;

        if ($this->getPaymentsReceivedTotal('usd') == $this->amount_usd)
            return true;

        return false;
    }

    /**
     * Get the total of the payments for this invoice
     *
     * @param string $type
     * @return mixed
     */
    public function getPaymentsReceivedTotal($type = "cad") {
        if ($type == "cad")
            return $this->payments->sum('amount_cad');

        return $this->payments->sum('amount_usd');
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


    /*
     * Relationships
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project() {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client() {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contract() {
        return $this->belongsTo(Contract::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function works() {
        return $this->hasMany(Work::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function payments() {
        return $this->belongsToMany(Payment::class);
    }
}