<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Martin\Clients\Service;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Service::all()->toArray(), 200);
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
            'description'       => 'required',
            'rate'              => 'required|numeric',
            'billing_frequency' => 'required',
            'activated_at'      => 'required|date:Y-m-d',
            'deactivated_at'    => 'nullable|date:Y-m-d',
            'valid_from_date'   => 'required|date:Y-m-d',
            'valid_until_date'  => 'nullable|date:Y-m-d',
        ]);

        $service = Service::create($validData);
        return response($service->toArray(),
            201,
            ['Location' => '/api/services/' . $service->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response($service->toArray(), 200);
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
        $service = Service::findOrFail($id);

        $validData = $request->validate([
            'project_id'        => 'required|exists:projects,id',
            'description'       => 'required',
            'rate'              => 'required|numeric',
            'billing_frequency' => 'required',
            'activated_at'      => 'required|date:Y-m-d',
            'deactivated_at'    => 'nullable|date:Y-m-d',
            'valid_from_date'   => 'required|date:Y-m-d',
            'valid_until_date'  => 'nullable|date:Y-m-d',
        ]);

        $service->update($validData);
        return response($service->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response('', 204);
    }
}
