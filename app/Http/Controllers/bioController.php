<?php

namespace App\Http\Controllers;

use App\golfWeek;
use App\groupMembers;
use App\group;
use App\users;
use Illuminate\Http\Request;

class bioController extends Controller
{

    protected $uid      = 0;
    protected $uname    = 0;

    public function __construct($uid = 0)
    {
        $this->uid      = $uid;
        $this->uname    = users::getNameFromId($uid);
    }


    /**
     * stuff can add: wheather inegration
     *
     * @return void
     */
    public function getBio()
    {
        $golf = new golfController();
        return array(
            'scores'        => $golf->score($this->uid),
            'scoreCount'    => $golf->scoreCount()[$this->uname],
            'parAccuracy'   => $golf->parAccuracy()[$this->uname],
            'range'         => $golf->range()[$this->uname],
        );
    }
}
