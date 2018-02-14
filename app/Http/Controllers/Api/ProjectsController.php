<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Martin\Projects\Project;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Project::all()->toArray(), 200);
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
            'client_id'     => 'required|exists:clients,id',
            'name'          => 'required',
            'code'          => 'required|unique:projects,code',
            'description'   => 'nullable',
            'status'        => 'nullable',
            'started_at'    => 'nullable|date:Y-m-d',
            'git_repo_url'      => 'nullable',
            'production_url'    => 'nullable',
            'staging_url'       => 'nullable',
            'development_url'   => 'nullable',
        ]);

        $project = Project::create($validData);
        return response($project->toArray(),
            201,
            ['Location' => '/api/projects/' . $project->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return response($project->toArray(), 200);
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
        $project = Project::findOrFail($id);

        $validData = $request->validate([
            'client_id'     => 'required|exists:clients,id',
            'name'          => 'required',
            'code'          => 'required|unique:projects,code,' . $project->id,
            'description'   => 'nullable',
            'status'        => 'nullable',
            'started_at'    => 'nullable|date:Y-m-d',
            'git_repo_url'      => 'nullable',
            'production_url'    => 'nullable',
            'staging_url'       => 'nullable',
            'development_url'   => 'nullable',
        ]);

        $project->update($validData);
        return response($project->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response('', 204);
    }
}
