<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Martin\Billing\Charge;

class ChargesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Charge::all()->toArray(), 200);
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
            'invoice_id'        => 'nullable|exists:invoices,id',
            'chargeable_id'     => 'required',
            'chargeable_type'   => 'required',
            'rate'              => 'required|numeric',
            'quantity'          => 'nullable|numeric',
            'total_cost'        => 'nullable|numeric',
            'billable_as_of'    => 'nullable|date:Y-m-d',
            'billed_at'         => 'nullable|date:Y-m-d',
        ]);

        $charge = Charge::create($validData);
        return response($charge->toArray(),
            201,
            ['Location' => '/api/charges/' . $charge->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $charge = Charge::findOrFail($id);
        return response($charge->toArray(), 200);
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
        $charge = Charge::findOrFail($id);

        $validData = $request->validate([
            'project_id'        => 'required|exists:projects,id',
            'invoice_id'        => 'nullable|exists:invoices,id',
            'chargeable_id'     => 'required',
            'chargeable_type'   => 'required',
            'rate'              => 'required|numeric',
            'quantity'          => 'nullable|numeric',
            'total_cost'        => 'nullable|numeric',
            'billable_as_of'    => 'nullable|date:Y-m-d',
            'billed_at'         => 'nullable|date:Y-m-d',
        ]);

        $charge->update($validData);
        return response($charge->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $charge = Charge::findOrFail($id);
        $charge->delete();

        return response('', 204);
    }
}
