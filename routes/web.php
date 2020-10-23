<?php

use App\group;
use App\Http\Controllers\golfController;
use GuzzleHttp\Psr7\Request;
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

Auth::routes();

Route::get('',              'HomeController@home')->name('home');
//reduce load speed by using this method
Route::get('/{any}', function ($any) {
    echo $any;
    switch($any){
        case 'tempUser':        return (new App\Http\Controllers\tempUserController())->create();

        case 'player':          return (new App\Http\Controllers\HomeController())->pageBio(Request());
        case 'week':            return (new App\Http\Controllers\HomeController())->pageWeek(Request());

        case 'scores':          return (new App\Http\Controllers\scoresSettingsController())->pageScores(Request());
        case 'setscores':       return (new App\Http\Controllers\scoresSettingsController())->setScores(Request());
        case 'updatescores':    return (new App\Http\Controllers\scoresSettingsController())->updateScores(Request());

        case 'newgroup':        return (new App\Http\Controllers\newGroupController())->pageNewGroup(Request());
        case 'joingroup':       return (new App\Http\Controllers\lobbyController())->join(Request());
        case 'lobby':           return (new App\Http\Controllers\newGroupController())->pageLobby(Request());
        case 'newgroup/create': return (new App\Http\Controllers\newGroupController())->create(Request());
        case 'newgroup/join': return (new App\Http\Controllers\newGroupController())->join(Request());
        case 'changegroup':     return (new App\Http\Controllers\newGroupController())->pageChangeGroup(Request());

        case 'groupsettings':   return (new App\Http\Controllers\groupSettingsController())->pageSettings(Request());
        case 'updatecourse':    return (new App\Http\Controllers\groupSettingsController())->updateCourse(Request());
        case 'setcourse':       return (new App\Http\Controllers\groupSettingsController())->setCourse(Request());
        case 'removeplayer':    return (new App\Http\Controllers\groupSettingsController())->removePlayer(Request());

    }
});

