<?php

use Illuminate\Http\Request;

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
Route::get('/', function () {
    return view('welcome');
});
Route::post('create', 'OsoiteApi@createPerson')->middleware('cors');
Route::get('hae', 'OsoiteApi@hae');
Route::get('persons', 'OsoiteApi@getAllPersons')->middleware('cors');
Route::get('persons/byzip', 'OsoiteApi@getPersonsByZip')->middleware('cors');
Route::get('persons/byfirstname', 'OsoiteApi@getPersonsByfirstName')->middleware('cors');
Route::get('persons/bylastname', 'OsoiteApi@getPersonsByLastName')->middleware('cors');
Route::get('persons/byfirstandlast', 'OsoiteApi@getPersonsByfirstNameAndLastName')->middleware('cors');
Route::get('persons/byanyname', 'OsoiteApi@getPersonsByAnyName')->middleware('cors');
Route::get('persons/byanynameandzip', 'OsoiteApi@getPersonsByAnyNameAndZip')->middleware('cors');
Route::get('persons/byfullnameandzip', 'OsoiteApi@getPersonsByfirstNameAndLastNameAndZip')->middleware('cors');