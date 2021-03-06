<?php

namespace App\Http\Controllers;

use App\golfCorse;
use App\golfWeek;
use App\group;
use App\groupMembers;
use App\groupSettings;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * class for all home/view related funcions
 */
class HomeController extends Controller
{
    /**
     * load middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('firstTimeSetup');
        $this->middleware('noData');
    }


    /**
     * Show home page depicting the groups total scores.
     *
     * @param Request $request
     * @return view(home)
     */
    public function home()
    {
        $golf = new golfController();
        return view('home', $golf->getTotal() + $this->headerData());
    }


    /**
     * display bio page for given $player
     *
     * @param Request $request
     * @return view(bio)
     */
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


    /**
     * display week page for given $week
     *
     * @param Request $request
     * @return view(week)
     */
    public function pageWeek(Request $request)
    {
        $week = $request->get('week');
        //check if week
        if (count(golfWeek::where('groupid', group::currentGroupId())->where('weeknumber', $week)->get()) > 0) {
            $golf = new golfController();
            return view('week', $golf->getWeek($week) + $this->headerData() + array('week' => $week,));
        }
    }




    /**
     * retruns an array of data needed for the page header/menu to function correctly
     *
     * @return array(
     *      weeks   => golfWeek,
     *      players => DB::SELECT(players),
     *      isAdmin => group::isAdmin(),
     *      group   => group::currentGroup(),
     *      groups  => group::getUserGroups() )
     */
    public function headerData()
    {
        return array(
            'weeks'     => golfWeek::where('groupid', group::currentGroupId())->get(),
            'players'   => DB::select('SELECT u.name,gm.admin FROM users u INNER JOIN group_members gm ON gm.userid = u.id WHERE gm.groupid = ' . group::currentGroupId()),
            'isAdmin'   => group::isAdmin(),
            'group'     => group::currentGroup(),
            'groups'    => group::getUserGroups(),
            'settings'  => groupSettings::getGroupSettings(),
        );
    }
}
