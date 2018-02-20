<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Martin\Tracking\Device;

class DevicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Device::all()->toArray(), 200);
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
            'name'          => 'required',
            'description'   => 'nullable',
            'purchased_at'  => 'required|date:Y-m-d',
            'cost'          => 'required|numeric',
            'notes'         => 'nullable',
        ]);

        $device = Device::create($validData);
        return response($device->toArray(),
            201,
            ['Location' => '/api/devices/' . $device->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $device = Device::findOrFail($id);
        return response($device->toArray(), 200);
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
        $device = Device::findOrFail($id);

        $validData = $request->validate([
            'name'          => 'required',
            'description'   => 'nullable',
            'purchased_at'  => 'required|date:Y-m-d',
            'cost'          => 'required|numeric',
            'notes'         => 'nullable',
        ]);

        $device->update($validData);
        return response($device->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();

        return response('', 204);
    }
}
