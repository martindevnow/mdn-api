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

    /**
     * Mass-assignable fields
     *
     * @var array
     */
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

    /**
     * Fields to cast as Carbon/Carbon
     *
     * @var array
     */
    protected $dates = [
        'generated_at' => 'date:Y-m-d',
        'sent_at' => 'date:Y-m-d',
        'paid_at => \'date:Y-m-d\''
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


    /**
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