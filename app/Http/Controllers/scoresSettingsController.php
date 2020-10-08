<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\golfCorse;
use App\golfHole;
use App\golfScore;
use App\golfWeek;
use App\group;
use App\groupMembers;
use App\groupSettings;
use Illuminate\Support\Facades\DB;

/**
 * functions and view related to scores settings page
 */
class scoresSettingsController extends Controller
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
     * display scores settings page
     *
     * @param Request $request
     * @return view(scores)
     */
    public function pageScores(Request $request)
    {
        if (!group::isAdmin()) {
            return redirect('/');
        }
        foreach (groupSettings::where('groupid', group::currentGroupId())->get() as $setting) {
            $settings[$setting->name] = $setting->value;
        }
        return view('scores', (new HomeController())->headerData() + array(
            'settings'  => $settings,
            'corses'    => golfCorse::where('groupid', group::currentGroupId())->get(),
            'weeks'     => golfWeek::where('groupid', group::currentGroupId())->get(),
            'oldScores' => (new golfController())->scores(),
        ));
    }


    /**
     * set posted scores into data, creates a new week and corse if neccessery
     *
     * @param Request $request
     * @return redirect(root)
     */
    public function setScores(Request $request)
    {
        if (!group::isAdmin()) {
            return redirect('/');
        }
        $gid = group::currentGroupId();
        $weekcount = count(golfWeek::where('groupid', $gid)->get()) + 1;
        $settings = groupSettings::getGroupSettings();
        if ($request->get('corse') == '' || $request->get('corse') == 'new') {
            $golfcorse = new golfCorse();
            $golfcorse->name = 'week ' . $weekcount;
            $golfcorse->groupid = $gid;
            $golfcorse->save();
            $corseid = $golfcorse->id;
            //fix hole issue
            for ($i = 0; $i < $settings['hole_number']; $i++) {
                $hole = new golfHole();
                $hole->corseid = $corseid;
                $hole->hole    = ($i + 1);
                $hole->range   = 0;
                $hole->save();
            }
        } else {
            $corseid = (golfCorse::where('groupid', $gid)->where('name', $request->get('corse'))->first())->id;
        }
        $week = new golfWeek();
        $week->weeknumber = $weekcount;
        $week->groupid    = $gid;
        $week->corseid    = $corseid;
        $week->save();
        foreach (DB::select('SELECT u.name,u.id FROM users u INNER JOIN group_members gm ON gm.userid = u.id WHERE gm.groupid = ' . $gid) as $player) {
            for ($i = 0; $i < $settings['hole_number']; $i++) {
                $s          = new golfScore();
                $s->week    = $weekcount;
                $s->hole    = ($i + 1);
                $s->user    = $player->id;
                $s->score   = ($request->get($player->name . '-' . ($i + 1)) && $request->get($player->name . '-' . ($i + 1)) % 1 == 0) ? $request->get($player->name . '-' . ($i + 1)) : 10;
                $s->groupid = $gid;
                $s->save();
            }
        }
        return redirect('/week?week=' . $weekcount);
    }


    /**
     * update old scores in database and redirect to updated week
     *
     * @return redirect
     */
    public function updateScores(Request $request)
    {
        if (!group::isAdmin()) {
            return redirect('/');
        }
        //double check no ones messed with the week number
        $foulplay = TRUE;
        foreach (golfWeek::where('groupid', group::currentGroupId())->get() as $week) {
            if ($week->weeknumber == $request->get('week')) {
                $foulplay = false;
            }
        }
        if ($foulplay) {
            return redirect('/scores');
        }
        //update corse
        $week = golfWeek::where('groupid', group::currentGroupId())->where('weeknumber', $request->get('week'))->first();
        $corse = golfCorse::where('groupid', group::currentGroupId())->where('name', $request->get('corse'))->first();
        if ($week->corseid != $corse->id) {
            $week->corseid = $corse->id;
            $week->save();
        }
        //update scores
        $settings = groupSettings::getGroupSettings();
        foreach (DB::select('SELECT s.id, u.name, s.hole FROM golf_score s INNER JOIN users u ON s.user = u.id WHERE s.week = ? AND groupid = ?', [$request->get('week'), group::currentGroupId()]) as $score) {
            $s = golfScore::find($score->id);
            $s->score = ($request->get($score->name . '-' . $request->get('week') . '-' . $score->hole) && $request->get($score->name . '-' . $request->get('week') . '-' . $score->hole) % 1 == 0) ? $request->get($score->name . '-' . $request->get('week') . '-' . $score->hole) : $s->score;
            $s->save();
        }
        return redirect('/week?week=' . $request->get('week'));
    }
}
