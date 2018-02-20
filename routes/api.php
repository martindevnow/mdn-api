<?php

use Illuminate\Http\Request;
use Martin\Clients\Client;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('/changeRequests', 'ChangeRequestsController');
Route::apiResource('/charges', 'ChargesController');
Route::apiResource('/clients', 'ClientsController');
Route::apiResource('/contacts', 'ContactsController');
Route::apiResource('/contracts', 'ContractsController');
Route::apiResource('/devices', 'DevicesController');
Route::apiResource('/domains', 'DomainsController');
Route::apiResource('/invoices', 'InvoicesController');
Route::apiResource('/payments', 'PaymentsController');
Route::apiResource('/projects', 'ProjectsController');
Route::apiResource('/servers', 'ServersController');
Route::apiResource('/services', 'ServicesController');
Route::apiResource('/works', 'WorksController');