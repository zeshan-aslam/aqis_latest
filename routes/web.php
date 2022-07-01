<?php

use Carbon\Carbon;

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
	return view('app');
});
Route::get('/bulk', function(){
    return view('mail.client.bulkmail');
});
Route::get('/test', function () {
    $month = 02;
    $year = 2018;

    $startDate = Carbon::create($year, $month, 01, null);
//    print($startDate);
    echo("<br>");
    $endDate = $startDate->endOfMonth();
//    print($endDate);
    $data = array(
        'startDate' => $startDate,
        'endDate' => $endDate
    );

    return view('test')->with($data);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
