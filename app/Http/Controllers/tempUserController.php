<?php

namespace App\Http\Controllers;

use App\golfCorse;
use App\golfHole;
use App\golfScore;
use App\golfWeek;
use App\group;
use App\groupMembers;
use App\groupSettings;
use App\users;
use App\usersTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class tempUserController extends Controller
{

    /**
     * create temp user for demo
     *
     * @return void
     */
    public function Create()
    {
        if (Auth::user()) {
            //only create for users not logged in
            return redirect('/');
        }
        $name = 'TempUser-' . time();
        //create temp user
        $new_user = new users();
        $new_user->name     = $name;
        $new_user->email    = "$name@$name.com";
        $new_user->password = '$2y$10$2esRGWj1hddTzvLWbLD1GOtYrTPldlw6M5wFouHr8BAoT0daJcxEu';
        $new_user->save();
        $newid = $new_user->id;
        //register as temp user
        $tu = new usersTemp();
        $tu->uid = $newid;
        $tu->save();
        //create Group
        $group = new group();
        $group->name = 'tempGroup-' . time();
        $group->save();
        $gid = $group->id;
        //add cource
        $gc = new golfCorse();
        $gc->name = 'Green Hill Zone';
        $gc->groupid = $gid;
        $gc->save();
        $gcid = $gc->id;
        //generate holes
        foreach (array(1 => 51, 2 => 46, 3 => 49, 4 => 57, 5 => 51, 6 => 57, 7 => 46, 8 => 64, 9 => 59, 10 => 45, 11 => 58, 12 => 42) as $h => $r) {
            $gh = new golfHole();
            $gh->corseid = $gcid;
            $gh->hole = $h;
            $gh->range = $r;
            $gh->save();
        }

        //generate settings
        $gs = new groupSettings();
        $gs->groupid = $gid;
        $gs->name = 'hole_number';
        $gs->value = 12;
        $gs->save();
        $gs = new groupSettings();
        $gs->groupid = $gid;
        $gs->name = 'points_system';
        $gs->value = 0;
        $gs->save();
        //add members to group
        foreach (array(6, 7, 8, $newid) as $foo) {
            $gm = new groupMembers();
            $gm->userid = $foo;
            $gm->groupid = $gid;
            $gm->admin = ($foo == $newid) ? 1 : 0;
            $gm->save();
        }
        //generate scores and weeks
        foreach (array(1, 2, 3) as $wn) {
            $wk = new golfWeek();
            $wk->corseid = $gcid;
            $wk->groupid = $gid;
            $wk->weeknumber = $wn;
            $wk->save();
            foreach (array(6, 7, 8, $newid) as $foo) {
                for ($i = 1; $i != 13; $i++) {
                    $gsc = new golfScore();
                    $gsc->user = $foo;
                    $gsc->week = $wn;
                    $gsc->groupid = $gid;
                    $gsc->hole = $i;
                    $gsc->score = rand(1, 6);
                    $gsc->save();
                }
            }
        }
        //login as temp user and display home page
        Auth::loginUsingId($newid, true);
        return redirect('/');
    }


    /**
     * destroys all temp users currently not in use
     */
    protected function destroy()
    {
        //only work if im logged in
        /*
        if (Auth::id() == 1) {
            foreach (usersTemp::all() as $temp) {

            }
            return redirect('/');
        }
        abort(404);
        */
    }
}
