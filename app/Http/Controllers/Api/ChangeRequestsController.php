<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Martin\Clients\ChangeRequest;

class ChangeRequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(ChangeRequest::all()->toArray(), 200);
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
            'user_id'                   => 'required|exists:users,id',
            'description'               => 'required',
            'fulfilled_at'              => 'nullable|date:Y-m-d',
            'requested_at'              => 'nullable|date:Y-m-d',
        ]);

        $changeRequest = ChangeRequest::create($validData);
        return response($changeRequest->toArray(),
            201,
            ['Location' => '/api/changeRequests/' . $changeRequest->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $changeRequest = ChangeRequest::findOrFail($id);
        return response($changeRequest->toArray(), 200);
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
        $changeRequest = ChangeRequest::findOrFail($id);

        $validData = $request->validate([
            'project_id'                => 'required|exists:projects,id',
            'user_id'                   => 'required|exists:users,id',
            'description'               => 'required',
            'fulfilled_at'              => 'nullable|date:Y-m-d',
            'requested_at'              => 'nullable|date:Y-m-d',
        ]);

        $changeRequest->update($validData);
        return response($changeRequest->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $changeRequest = ChangeRequest::findOrFail($id);
        $changeRequest->delete();

        return response('', 204);
    }
}
