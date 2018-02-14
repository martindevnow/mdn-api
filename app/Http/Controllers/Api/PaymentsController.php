<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Martin\Billing\Payment;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Payment::all()->toArray(), 200);
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
            'client_id'         => 'required|exists:clients,id',
            'cheque_number'     => 'required',
            'amount_cad'        => 'required|numeric',
            'amount_usd'        => 'required|numeric',
            'usd_to_cad_rate'   => 'nullable|numeric',
            'received_at'       => 'nullable|date:Y-m-d',
        ]);

        $payment = Payment::create($validData);
        return response($payment->toArray(),
            201,
            ['Location' => '/api/payments/' . $payment->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return response($payment->toArray(), 200);
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
        $payment = Payment::findOrFail($id);

        $validData = $request->validate([
            'client_id'         => 'required|exists:clients,id',
            'cheque_number'     => 'required',
            'amount_cad'        => 'required|numeric',
            'amount_usd'        => 'required|numeric',
            'usd_to_cad_rate'   => 'nullable|numeric',
            'received_at'       => 'nullable|date:Y-m-d',
        ]);

        $payment->update($validData);
        return response($payment->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response('', 204);
    }
}
