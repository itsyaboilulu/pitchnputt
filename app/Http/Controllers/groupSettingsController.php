<?php

namespace App\Http\Controllers;

use App\golfCorse;
use App\golfHole;
use App\golfScore;
use App\group;
use App\groupMembers;
use App\groupSettings;
use App\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class groupSettingsController extends Controller
{

    /**
     * load middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('firstTimeSetup');
    }


    /**
     * displays group settings page
     *
     * @return view groupsettings
     */
    public function pageSettings()
    {
        if (!group::isAdmin()) {
            return redirect('/');
        }
        $golfCorse = golfCorse::where('groupid', group::currentGroupId())->get();
        $holes = [];
        foreach ($golfCorse as $gc) {
            $holes[$gc->name] = golfHole::where('corseid', $gc->id)->get();
        }
        return view('groupSettings', (new HomeController())->headerData() + array(
            'settings' => groupSettings::getGroupSettings(),
            'corses'   => $golfCorse,
            'holes'    => $holes
        ));
    }


    /**
     * updates course data
     *
     * @param Request $request
     * @return redirect /groupsettings
     */
    public function updateCourse(Request $request)
    {
        if (!group::isAdmin()) {
            return redirect('/');
        }
        if ($request->get('id')) {
            $corse = golfCorse::find($request->get('id'));
            //stop changeing of random ids
            if ($corse->groupid == group::currentGroupId()) {
                foreach (golfHole::where('corseid', $corse->id)->get() as $hole) {
                    $edithole = golfHole::find($hole->id);
                    $range = $request->get('yards-' . $hole->hole);
                    $edithole->range = (is_numeric($range)) ? $range : 0;
                    $edithole->save();
                }
                $corse->name = ($request->get('name')) ? $request->get('name') : $corse->name;
                $corse->save();
            }
        }
        return redirect('/groupsettings');
    }


    /**
     * creates new course from passed data
     *
     * @param Request $request
     * @return redirct /groupsettings
     */
    public function setCourse(Request $request)
    {
        if (!group::isAdmin()) {
            return redirect('/');
        }
        if ($request->get('name')) {
            //check if name is alrady used
            if (count(golfCorse::where('groupid', group::currentGroupId())->where('name', $request->get('name'))->get()) == 0) {
                $corse = new golfCorse();
                $corse->groupid = group::currentGroupId();
                $corse->name = ($request->get('name')) ? $request->get('name') : $corse->name;
                $corse->save();
                for ($i = 1; $i != (groupSettings::getGroupSettings()['hole_number'] + 1); $i++) {
                    $newHole = new golfHole();
                    $newHole->hole = $i;
                    $newHole->corseid = $corse->id;
                    $range = $request->get('yards-' . $i);
                    $newHole->range = (is_numeric($range)) ? $range : 0;
                    $newHole->save();
                }
            }
        }
        return redirect('/groupsettings');
    }


    /**
     * remove player from current group
     *
     * @param Request $request
     * @return redirect /
     */
    public function removePlayer(Request $request)
    {
        if (!group::isAdmin()) {
            return redirect('/');
        }
        if ($request->get('name') && $request->get('name') != Auth::user()->name) {
            $user = users::where('name', $request->get('name'))->first();
            $gid = group::currentGroupId();
            //remove from group members
            DB::delete('DELETE FROM group_members where groupid = ? AND userid = ?', [$gid, $user->id]);
            //remove scores
            foreach (golfScore::where('groupid', $gid)->where('user', $user->id)->get() as $gs) {
                $score = golfScore::find($gs->id);
                $score->delete();
            }
            //generate new join code
            DB::update('UPDATE group_settings set value = ? where groupid = ? AND name="join_code"', [groupSettings::generateJoinCode(), $gid]);
        }
        return redirect('/groupsettings');
    }
}
