<?php

use App\group;
use Illuminate\Support\Facades\Route;

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


Auth::routes();

//Route::get('/', function () { return redirect('login'); });

Route::get('',              'HomeController@home')->name('home');
Route::get('/player',       'HomeController@pageBio');
Route::get('/week',         'HomeController@pageWeek');
Route::get('/scores',       'scoresController@pageScores');
Route::post('/setscores',       'scoresController@setScores');
Route::get('/setgroup',     'HomeController@pageSetGroup');



/**
 * dev tools wont be in live version
 */
if (env('APP_DEBUG')) {
    Route::get('/dev/modelmaker', 'devToolBoxController@modelMaker');
    Route::get('/test', function () {
        print_r(group::currentGroupId());
    });
}
