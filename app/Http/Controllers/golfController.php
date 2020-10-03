<?php

namespace App\Http\Controllers;

use App\golfHole;
use App\golfScore;
use App\golfWeek;
use App\group;
use App\groupMembers;
use App\groupSettings;
use App\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class golfController extends Controller
{

    protected $scores_data = NULL;
    protected $group = 0;

    /**
     * Undocumented function
     *
     * @param integer $group
     */
    public function __construct($group = 0)
    {
        $this->group = ($group) ? $group : group::currentGroupId();
    }

    /** -------------- Public Functions ---------------- **/

    /**
     * returns all the information used in the total page (/)
     *
     * @return array
     */
    public function GetTotal()
    {
        return array(
            'scores'        => $this->scores(),
            'position'      => $this->positions(),
            'parAccuracy'   => $this->parAccuracy(),
            'consistant'    => $this->consistancy(),
            'range'         => $this->range(),
        );
    }


    /**
     * returns all the information used in the total page (/week)
     *
     * @param int $week
     * @return void
     */
    public function GetWeek($week)
    {
        return array(
            'scores'      => $this->score(0, $week),
            'parAccuracy' => $this->parAccuracy($week),
            'consistant'  => $this->consistancy($week),
            'range'       => $this->range($week),
        );
    }

    /**
     * collects and returns all users scores data, stores in memory to
     * reduce strain on sql server
     *
     * @return $this->scores_data
     */
    public function scores()
    {
        if (!$this->scores_data) {
            foreach (groupMembers::where('groupid', $this->group)->get() as $u) {
                $ret = array();
                foreach (golfWeek::where('groupid', $this->group)->get() as $gw) {
                    $sub_ret = [];
                    foreach (golfScore::where('week', $gw->weeknumber)->where('user', $u->userid)->where('groupid', $this->group)->get() as $gs) {
                        $sub_ret[] = $gs->score;
                    }
                    $ret[$gw->weeknumber] = $sub_ret;
                }
                $this->scores_data[users::getNameFromId($u->userid)] = $ret;
            }
        }
        return $this->scores_data;
    }


    /**
     * filters and returns of $this->scores_data
     *
     * @param integer $uid player id
     * @param integer $weekid week number
     * @param integer $holeid hole number
     *
     * @return array
     */
    public function score($uid = 0, $weekid = 0, $holeid = 0)
    {

        $data = $this->scores();

        if ($holeid) {

            foreach ($data as $name => $sub_data) {
                foreach ($sub_data as $week => $sub_sub_sdata) {
                    $sub_data[$week] = $sub_sub_sdata[($holeid - 1)];
                }
                $data[$name] = $sub_data;
            }
        }

        if ($weekid) {

            foreach ($data as $name => $sub_data) {
                $data[$name] = $sub_data[$weekid];
            }
        }

        if ($uid) {
            $data = $data[users::getNameFromId($uid)];
        }

        return $data;
    }


    /**
     * returns players position in current tourniment, ordered by postion 1->5 and
     * gives players score
     *
     * @param int $week week number
     * @return array { name => score }
     */
    public function positions($week = 0)
    {
        foreach ($this->score(0, $week, 0) as $name => $data) {
            if ($week) {
                $ret[$name] = array_sum($data);
            } else {
                $total = 0;
                foreach ($data as $week_data) {
                    $total += array_sum($week_data);
                }
                $ret[$name] = $total;
            }
        }
        asort($ret);
        if ((groupSettings::where('groupid', $this->group)->where('name', 'points_system')->first())->value) {
            //use a per game pints system aposed to lowest points
            if ($week) {
                $i = 0;
                foreach ($ret as $n => $p) {
                    $ret[$n] = count($ret) - $i;
                    $i++;
                }
            } else {
                //recure becouse its esayer then retyping the 1st half
                $ret = array();
                for ($i = 0; $i < count($data); $i++) {
                    foreach ($this->positions($i + 1) as $n => $p) {
                        $ret[$n] = (isset($ret[$n])) ? ($ret[$n] + $p) : $p;
                    };
                }
            }
            arsort($ret);
        }

        return $ret;
    }


    /**
     * returns list of users with there counted scores ( e.g. 3:2,4:5 ),
     * can be filtered into weeks and hole
     *
     * @important function does not work is both $week and $hole are set,
     *      options are either $hole, $week or neither
     *
     * @param integer $week week number
     * @param integer $hole hole number
     * @return array
     */
    public function scoreCount($week = 0, $hole = 0)
    {
        foreach ($this->score(0, $week, $hole) as $name => $data) {
            $count = array();
            if (($week && !$hole) || (!$week && $hole)) {
                foreach ($data as $d) {
                    $count[$d] = (isset($count[$d])) ? $count[$d] + 1 : 1;
                }
                ksort($count);
                $scores_count[$name] = $count;
            } else {
                $hcount = null;
                foreach ($data as $wid => $sub_data) {
                    $count = array();
                    foreach ($sub_data as $h => $d) {
                        $count[$d] = (isset($count[$d])) ? $count[$d] + 1 : 1;
                        $hcount[$h][$d] = (isset($hcount[$h][$d])) ? $hcount[$h][$d] + 1 : 1;
                        $scores_count[$name]['total'][$d] = (isset($scores_count[$name]['total'][$d])) ? $scores_count[$name]['total'][$d] + 1 : 1;
                    }
                    ksort($count);
                    $scores_count[$name]['week'][$wid] = $count;
                }
                $scores_count[$name]['hole'] = $hcount;
            }
        }
        return $scores_count;
    }


    /**
     * returns each players par accuracy (% of getting 3) as well as group,
     * can be procken down into weeks and holes, if emply gets total for all games
     *
     * @important function does not work is both $week and $hole are set,
     *      options are either $hole, $week or neither
     *
     * @param integer $week week number
     * @return array( [ name=>acc ] , group_acc )
     */
    public function parAccuracy($week = 0)
    {
        foreach ($this->score(0, $week) as $name => $data) {

            if ($week) {
                if (!isset($ret['total'])) {
                    $ret['total'] = [0, 0, 0];
                }
                $po = 0;
                $p  = 0;
                $pu = 0;
                foreach ($data as $d) {
                    switch (TRUE) {
                        case $d > 3:
                            $po++;
                            $ret['total'][2]++;
                            break;
                        case $d < 3:
                            $pu++;
                            $ret['total'][0]++;
                            break;
                        case $d == 3:
                            $p++;
                            $ret['total'][1]++;
                            break;
                    }
                }
                $ret[$name] =
                    [(($pu / ($pu + $p + $po)) * 100), (($p / ($pu + $p + $po)) * 100), (($po / ($pu + $p + $po)) * 100)];
            } else {
                $ret[$name]['total'] = [0, 0, 0];
                foreach ($data as $wk => $wdata) {
                    $po = 0;
                    $p  = 0;
                    $pu = 0;
                    foreach ($wdata as $h => $s) {
                        if ($s > 3 && $s != 3) {
                            $po++;
                            $ret[$name]['hole'][$h][2] =  (isset($ret[$name]['hole'][$h][2])) ? $ret[$name]['hole'][$h][2] + 1 : 1;
                            $ret[$name]['total'][2]++;
                        } elseif ($s < 3 && $s != 3) {
                            $pu++;
                            $ret[$name]['hole'][$h][0] = (isset($ret[$name]['hole'][$h][0])) ? $ret[$name]['hole'][$h][0] + 1 : 1;
                            $ret[$name]['total'][0]++;
                        } else {
                            $p++;
                            $ret[$name]['hole'][$h][1] = (isset($ret[$name]['hole'][$h][1])) ? $ret[$name]['hole'][$h][1] + 1 : 1;
                            $ret[$name]['total'][1]++;
                        }
                    }
                    $ret[$name]['week'][$wk] = [(($pu / ($pu + $p + $po)) * 100), (($p / ($pu + $p + $po)) * 100), (($po / ($pu + $p + $po)) * 100)];
                }
                $ret[$name]['total'] = [(($ret[$name]['total'][0] / array_sum($ret[$name]['total'])) * 100), (($ret[$name]['total'][1] / array_sum($ret[$name]['total'])) * 100), (($ret[$name]['total'][2] / array_sum($ret[$name]['total'])) * 100)];
                foreach ($ret[$name]['hole'] as $h => $hd) {
                    $ret[$name]['hole'][$h] = [
                        ((isset($ret[$name]['hole'][$h][0])) ? (($ret[$name]['hole'][$h][0] / array_sum($ret[$name]['hole'][$h])) * 100) : 0),
                        ((isset($ret[$name]['hole'][$h][1])) ? (($ret[$name]['hole'][$h][1] / array_sum($ret[$name]['hole'][$h])) * 100) : 0),
                        ((isset($ret[$name]['hole'][$h][2])) ? (($ret[$name]['hole'][$h][2] / array_sum($ret[$name]['hole'][$h])) * 100) : 0),
                    ];
                }
            }
        }

        if ($week) {
            $ret['total'] = [
                ((($ret['total'][0] / array_sum($ret['total']))) * 100),
                ((($ret['total'][1] / array_sum($ret['total']))) * 100),
                ((($ret['total'][2] / array_sum($ret['total']))) * 100)
            ];
        }

        return $ret;
    }


    /**
     * gets each player (and groups) favourite range, based on lowest score to disance
     *
     * @return void
     */
    public function range($week = 0)
    {
        $used = array();
        $r_count = array();
        foreach (golfWeek::where('groupid', $this->group)->get() as $gw) {
            foreach (golfHole::where('corseid', $gw->corseid)->get() as $ghd) {
                $dists[$gw->weeknumber][] = $ghd->range;
                $r_count[$gw->weeknumber][$ghd->range] = (isset($r_count[$gw->weeknumber][$ghd->range])) ? $r_count[$gw->weeknumber][$ghd->range] + 1 : 1;
            }
            $used[$gw->corseid] = $gw->weeknumber;
        }
        foreach ($this->score(0, $week) as $name => $data) {
            if ($week) {
                foreach ($data as $h => $s) {
                    $ret[$name][$dists[$week][$h]] = (isset($ret[$name][$dists[$week][$h]])) ? $ret[$name][$dists[$week][$h]] + ($s /  $r_count[$week][$dists[$week][$h]]) : ($s /  $r_count[$week][$dists[$week][$h]]);
                }
            } else {
                foreach ($data as $w => $hs) {
                    foreach ($hs as $h => $s) {
                        $ret[$name]['week'][$w][$dists[$w][$h]] = (isset($ret[$name]['week'][$w][$dists[$w][$h]])) ? $ret[$name]['week'][$w][$dists[$w][$h]] + ($s /  $r_count[$w][$dists[$w][$h]]) : ($s /  $r_count[$w][$dists[$w][$h]]);
                        $ret[$name]['total'][$dists[$w][$h]] = (isset($ret[$name]['total'][$dists[$w][$h]])) ? $ret[$name]['total'][$dists[$w][$h]] + ($s /  $r_count[$w][$dists[$w][$h]]) : ($s /  $r_count[$w][$dists[$w][$h]]);
                    }
                    ksort($ret[$name]['week'][$w]);
                }
                ksort($ret[$name]['total']);
                foreach ($ret[$name]['total'] as $r => $t) {
                    $ret[$name]['avg'][$r] = ($t / count($data));
                }
            }
        }
        return $ret;
    }

    /**
     * returns a list of players based on consistancy( % to get the same score ),
     * can be filtered into weeks and hole
     *
     * @important function does not work is both $week and $hole are set,
     *      options are either $hole, $week or neither
     *
     * @param integer $week week number
     * @return array()
     */
    public function consistancy($week = 0)
    {
        foreach ($this->scoreCount($week) as $name => $data) {
            if ($week) {
                //s-score, c-count, t-total
                $s = array_keys($data, max($data))[0];
                $c = $data[array_keys($data, max($data))[0]];
                $t = 0;
                foreach ($data as $bar) {
                    $t += $bar;
                }
                $ret[$name] = [$s, ($c / $t) * 100];
            } else {
                foreach ($data as $w => $wdata) {
                    if ($w == 'total') {
                        $s = array_keys($wdata, max($wdata))[0];
                        $c = $wdata[array_keys($wdata, max($wdata))[0]];
                        $t = 0;
                        foreach ($wdata as $h => $bar) {
                            $t += $bar;
                        }
                        $ret[$name][$w] = [$s, ($c / $t) * 100];
                    } elseif ($w == 'hole') {
                        foreach ($wdata as $hdata) {
                            $s = array_keys($hdata, max($hdata))[0];
                            $c = $hdata[array_keys($hdata, max($hdata))[0]];
                            $t = 0;
                            foreach ($hdata as $h => $bar) {
                                $t += $bar;
                            }
                            $ret[$name][$w][] = [$s, ($c / $t) * 100];
                        }
                    }
                }
            }
        }
        return $ret;
    }

    /** -------------- Protected Functions ---------------- **/
}
