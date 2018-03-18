<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('hae', 'OsoiteApi@hae');
Route::get('persons', 'OsoiteApi@getAllPersons');
Route::get('persons/zip', 'OsoiteApi@getPersonsByZip');
Route::get('persons/byfirstname', 'OsoiteApi@getPersonsByfirstName');
Route::get('persons/bylastname', 'OsoiteApi@getPersonsByLastName');
Route::get('persons/byfirstandlast', 'OsoiteApi@getPersonsByfirstNameAndLastName');
Route::get('persons/byanyname', 'OsoiteApi@getPersonsByAnyName');