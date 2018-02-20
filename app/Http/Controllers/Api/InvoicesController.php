<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Martin\Billing\Invoice;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Invoice::all()->toArray(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validData = $request->validate([
            'project_id'        => 'required|exists:projects,id',
            'invoice_no'        => 'nullable',

            'amount_usd'        => 'nullable|numeric',
            'usd_to_cad_rate'   => 'nullable|numeric',
            'amount_cad'        => 'required|numeric',

            'generated_at'      => 'nullable|date:Y-m-d',
            'sent_at'           => 'nullable|date:Y-m-d',
            'paid_at'           => 'nullable|date:Y-m-d',
        ]);

        $invoice = Invoice::create($validData);
        return response($invoice->toArray(),
            201,
            ['Location' => '/api/invoices/' . $invoice->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        return response($invoice->toArray(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $validData = $request->validate([
            'project_id'        => 'required|exists:projects,id',
            'invoice_no'        => 'nullable',

            'amount_usd'        => 'nullable|numeric',
            'usd_to_cad_rate'   => 'nullable|numeric',
            'amount_cad'        => 'required|numeric',

            'generated_at'      => 'nullable|date:Y-m-d',
            'sent_at'           => 'nullable|date:Y-m-d',
            'paid_at'           => 'nullable|date:Y-m-d',
        ]);

        $invoice->update($validData);
        return response($invoice->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return response('', 204);
    }
}
