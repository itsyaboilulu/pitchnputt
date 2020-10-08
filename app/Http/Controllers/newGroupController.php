<?php

namespace App\Http\Controllers;

use App\golfScore;
use App\golfWeek;
use App\group;
use App\groupMembers;
use App\groupSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class newGroupController extends Controller
{
    /**
     * load middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * displays page for user to create a new group
     *
     * @return void
     */
    public function pageNewGroup()
    {
        return view('newGroup');
    }


    /**
     * creates a new group
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        //create group
        $group = new group();
        $group->name = $request->get('name');
        $group->save();
        $gid = $group->id;
        //create group settings
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
        //add user to group
        $gm = new groupMembers();
        $gm->userid = Auth::id();
        $gm->groupid = $gid;
        $gm->admin = 1;
        $gm->save();
        return redirect('changegroup?id=' . $gid);
    }


    /**
     * allows user to join a group
     *
     * @param Request $request
     * @return void
     */
    public function join(Request $request)
    {
        $code = $request->get('join');
        if (!$code) {
            return redirect('/login');
        }
        //get group
        $gs = groupSettings::where('name', 'join_code')->where('value', $code)->first();
        if (!$gs) {
            return redirect('/login');
        }
        $gid = $gs->groupid;
        //add user to group
        $groupmember = new groupMembers();
        $groupmember->userid = Auth::id();
        $groupmember->groupid = $gid;
        $groupmember->admin = 0;
        $groupmember->save();
        //add 10's to scors in prev weeks to stop crashes
        if (count(golfWeek::where('groupid', $gid)->get()) != 0) {
            foreach (golfWeek::where('groupid', $gid)->get() as $wk) {
                for ($i = 1; $i != 13; $i++) {
                    $gsc = new golfScore();
                    $gsc->user = Auth::id();
                    $gsc->week = $wk->weeknumber;
                    $gsc->groupid = $gid;
                    $gsc->hole = $i;
                    $gsc->score = 0;
                    $gsc->save();
                }
            }
        }
        return redirect('changegroup?id=' . $gid);
    }



    /**
     * allows user to change viewed group to given $id
     *
     * @param Request $request
     * @return redirect(root)
     */
    public function pageChangeGroup(Request $request)
    {
        if (group::isMember(Auth::id(), $request->get('id'))) {
            session(['group' => group::find($request->get('id'))]);
        }
        return redirect('/');
    }
}
