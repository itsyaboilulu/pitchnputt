<?php

namespace App\Http\Controllers;

use App\golfCorse;
use App\golfWeek;
use App\group;
use App\groupMembers;
use App\groupSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     */
    public function home()
    {

        $golf = new golfController();

        return view('home', $golf->getTotal() + $this->headerData());
    }

    public function pageBio(Request $request)
    {
        $id = group::isMember($request->get('player'));
        if ($id) {
            $bio = new bioController($id);
            return view('bio', $bio->getBio() + $this->headerData() + array(
                'player'    => $request->get('player'),
                'gsettings' => groupSettings::where('groupid', group::currentGroupId()),
            ));
        }
        return redirect('/');
    }

    public function pageWeek(Request $request)
    {
        $week = $request->get('week');
        //check if week
        if (count(golfWeek::where('groupid', group::currentGroupId())->where('weeknumber', $week)->get()) > 0) {
            $golf = new golfController();
            return view('week', $golf->getWeek($week) + $this->headerData() + array('week' => $week,));
        }
    }



    public function pageChangeGroup(Request $request)
    {
        if (group::isMember(Auth::id(), $request->get('id'))) {
            session(['group' => group::find($request->get('id'))]);
        }
        return redirect('/');
    }


    /**
     * retruns an array of data needed for the page header/menu to function correctly
     *
     * @return array
     */
    public function headerData()
    {
        return array(
            'weeks'     => golfWeek::where('groupid', group::currentGroupId())->get(),
            'players'   => DB::select('SELECT u.name,gm.admin FROM users u INNER JOIN group_members gm ON gm.userid = u.id WHERE gm.groupid = ' . group::currentGroupId()),
            'isAdmin'   => group::isAdmin(),
            'group'     => group::currentGroup(),
            'groups'    => group::getUserGroups(),
        );
    }
}
