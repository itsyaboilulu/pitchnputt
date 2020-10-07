<?php

use App\group;
use App\Http\Controllers\golfController;
use Illuminate\Support\Facades\Auth;
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


Route::get('/tempUser',      'tempUserController@create');

Auth::routes();

Route::get('',               'HomeController@home')->name('home');
Route::get('/player',        'HomeController@pageBio')->name('player');
Route::get('/week',          'HomeController@pageWeek')->name('week');
Route::get('/scores',        'scoresSettingsController@pageScores')->name('scores');
Route::get('/changegroup',   'HomeController@pageChangeGroup')->name('changeGroup');
Route::get('/groupsettings', 'groupSettingsController@pageSettings')->name('groupsettings');

Route::post('/setscores',    'scoresSettingsController@setScores');
Route::post('/updatescores', 'scoresSettingsController@updateScores');
Route::post('/updatecourse', 'groupSettingsController@updateCourse');
Route::post('/setcourse',    'groupSettingsController@setCourse')->name('setCourse');
Route::post('/removeplayer', 'groupSettingsController@removePlayer');

Route::get('/home', function () {
    return redirect()->route('home');
});

if (env('APP_DEBUG')) {
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
