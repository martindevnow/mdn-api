<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Martin\Tracking\Software;

class SoftwaresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Software::all()->toArray(), 200);
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
            'name'              => 'required',
            'description'       => 'nullable',
            'purchased_at'      => 'required|date:Y-m-d',
            'purchased_from'    => 'nullable',
            'amount_cad'        => 'required|numeric',
            'usd_to_cad_rate'   => 'nullable|numeric',
            'amount_usd'        => 'nullable|numeric',
            'billing_cycle'     => 'nullable',
        ]);

        $software = Software::create($validData);
        return response($software->toArray(),
            201,
            ['Location' => '/api/softwares/' . $software->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $software = Software::findOrFail($id);
        return response($software->toArray(), 200);
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
        $software = Software::findOrFail($id);

        $validData = $request->validate([
            'name'              => 'required',
            'description'       => 'nullable',
            'purchased_at'      => 'required|date:Y-m-d',
            'purchased_from'    => 'nullable',
            'amount_cad'        => 'required|numeric',
            'usd_to_cad_rate'   => 'nullable|numeric',
            'amount_usd'        => 'nullable|numeric',
            'billing_cycle'     => 'nullable',
        ]);

        $software->update($validData);
        return response($software->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $software = Software::findOrFail($id);
        $software->delete();

        return response('', 204);
    }
}
