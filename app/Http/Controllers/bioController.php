<?php

namespace App\Http\Controllers;

use App\golfWeek;
use App\groupMembers;
use App\group;
use App\users;
use Illuminate\Http\Request;

/**
 * class for all bio related funcions
 */
class bioController extends Controller
{
    /**
     * loaded users id
     *
     * @var integer
     */
    protected $uid      = 0;

    /**
     * loaded users name
     *
     * @var str
     */
    protected $uname    = 0;

    /**
     * set users name and id into storage
     *
     * @param integer $uid
     */
    public function __construct($uid = 0)
    {
        $this->uid      = $uid;
        $this->uname    = users::getNameFromId($uid);
    }


    /**
     * returns all data needed to display a users bio page for a given group
     *
     * @return array {
     *      scores => golf::score(),
     *      scoreCount => golf::scoreCount(),
     *      parAccuracy => golf::parAccuracy(),
     *      range => golf::range(),
     *  }
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
