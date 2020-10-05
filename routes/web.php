<?php

use App\group;
use App\Http\Controllers\golfController;
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

Route::get('',              'HomeController@home')->name('home');
Route::get('/player',       'HomeController@pageBio');
Route::get('/week',         'HomeController@pageWeek');
Route::get('/scores',       'scoresSettingsController@pageScores');
Route::post('/setscores',   'scoresSettingsController@setScores');
Route::get('/setgroup',     'HomeController@pageSetGroup');



/**
 * dev tools wont be in live version
 */
if (env('APP_DEBUG')) {
    Route::get('/dev/modelmaker', 'devToolBoxController@modelMaker');
    Route::get('/test', function () {
        $golf = new golfController(1);
        $pos = $golf->positions(2);
        print_r($pos[0]);
        echo '<hr>';
        print_r($pos[1]);
        echo '<hr>';
        print_r($pos[2]);
        echo '<hr>';
    });
}
