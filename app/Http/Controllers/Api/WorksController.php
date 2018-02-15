<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Martin\Projects\Work;

class WorksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Work::all()->toArray(), 200);
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
            'project_id'    => 'required|exists:projects,id',
            'details'       => 'required',
            'duration'      => 'numeric',
            'billable'      => 'required',
            'type'          => 'required',
            'performed_at'  => 'nullable|date:Y-m-d',
        ]);

        $work = Work::create($validData);
        return response($work->toArray(),
            201,
            ['Location' => '/api/works/' . $work->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $work = Work::findOrFail($id);
        return response($work->toArray(), 200);
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
        $work = Work::findOrFail($id);

        $validData = $request->validate([
            'project_id'    => 'required|exists:projects,id',
            'details'       => 'required',
            'duration'      => 'numeric',
            'billable'      => 'required',
            'type'          => 'required',
            'performed_at'  => 'nullable|date:Y-m-d',
        ]);

        $work->update($validData);
        return response($work->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $work = Work::findOrFail($id);
        $work->delete();

        return response('', 204);
    }
}
