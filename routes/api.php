<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
/*Route::post('import-csv', function (Request $request) {
    return response()->json([
        'test' => true,
        'name' => $request->file('csv_file')->getClientOriginalName()
    ]);
});*/


Route::post('import', [
    'as' => 'contact.import',
    'uses' => '\App\Http\Controllers\ContactAPIController@import'
]);

Route::get('contacts', [
    'as' => 'contact.index',
    'uses' => '\App\Http\Controllers\ContactAPIController@index'
]);
