<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Martin\Projects\Domain;

class DomainsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Domain::all()->toArray(), 200);
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
            'project_id'                => 'required|exists:projects,id',
            'name'                      => 'required',
            'registrar'                 => 'nullable',
            'originally_registered_at'  => 'nullable',
            'expires_at'                => 'nullable|date:Y-m-d',
        ]);

        $domain = Domain::create($validData);
        return response($domain->toArray(),
            201,
            ['Location' => '/api/domains/' . $domain->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $domain = Domain::findOrFail($id);
        return response($domain->toArray(), 200);
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
        $domain = Domain::findOrFail($id);

        $validData = $request->validate([
            'project_id'                => 'required|exists:projects,id',
            'name'                      => 'required',
            'registrar'                 => 'nullable',
            'originally_registered_at'  => 'nullable',
            'expires_at'                => 'nullable|date:Y-m-d',
        ]);

        $domain->update($validData);
        return response($domain->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $domain = Domain::findOrFail($id);
        $domain->delete();

        return response('', 204);
    }
}
