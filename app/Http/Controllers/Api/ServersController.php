<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Martin\Projects\Server;

class ServersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Server::all()->toArray(), 200);
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
            'host'          => 'required',
            'os'            => 'required',
            'username'      => 'nullable',
            'email'         => 'nullable',
            'purchased_at'  => 'nullable|date:Y-m-d',
            'expires_at'    => 'nullable|date:Y-m-d',

            'cost_monthly'  => 'required|numeric',
            'currency'      => 'required',
            'billing_cycle' => 'required',
            'active'        => 'nullable',
        ]);

        $server = Server::create($validData);
        return response($server->toArray(),
            201,
            ['Location' => '/api/servers/' . $server->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $server = Server::findOrFail($id);
        return response($server->toArray(), 200);
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
        $server = Server::findOrFail($id);

        $validData = $request->validate([
            'name'          => 'required',
            'host'          => 'required',
            'os'            => 'required',
            'username'      => 'nullable',
            'email'         => 'nullable',
            'purchased_at'  => 'nullable|date:Y-m-d',
            'expires_at'    => 'nullable|date:Y-m-d',

            'cost_monthly'  => 'required|numeric',
            'currency'      => 'required',
            'billing_cycle' => 'required',
            'active'        => 'nullable',
        ]);

        $server->update($validData);
        return response($server->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $server = Server::findOrFail($id);
        $server->delete();

        return response('', 204);
    }
}
