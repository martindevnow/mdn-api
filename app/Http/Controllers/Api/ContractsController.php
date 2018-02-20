<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Martin\Clients\Contract;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Contract::all()->toArray(), 200);
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
            'programming_hourly_rate'   => 'required|numeric',
            'sysadmin_hourly_rate'      => 'required|numeric',
            'consulting_hourly_rate'    => 'required|numeric',
            'activated_at'              => 'required|date:Y-m-d',
            'deactivated_at'            => 'nullable|date:Y-m-d',
            'valid_from_date'           => 'required|date:Y-m-d',
            'valid_until_date'          => 'nullable|date:Y-m-d',
        ]);

        $contract = Contract::create($validData);
        return response($contract->toArray(),
            201,
            ['Location' => '/api/contracts/' . $contract->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contract = Contract::findOrFail($id);
        return response($contract->toArray(), 200);
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
        $contract = Contract::findOrFail($id);

        $validData = $request->validate([
            'project_id'                => 'required|exists:projects,id',
            'programming_hourly_rate'   => 'required|numeric',
            'sysadmin_hourly_rate'      => 'required|numeric',
            'consulting_hourly_rate'    => 'required|numeric',
            'activated_at'              => 'required|date:Y-m-d',
            'deactivated_at'            => 'nullable|date:Y-m-d',
            'valid_from_date'           => 'required|date:Y-m-d',
            'valid_until_date'          => 'nullable|date:Y-m-d',
        ]);

        $contract->update($validData);
        return response($contract->fresh()->toArray(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();

        return response('', 204);
    }
}
