<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * model for golf:group
 * @param int $id PK
 * @param string $name
 */
class group extends Model
{
    public $timestamps = false;
    protected $table = 'group';


    /**
     *  returns id of sessioned group
     *
     *  @todo swap out for currentGroup()
     *
     * @return int
     */
    public static function currentGroupId()
    {
        return group::currentGroup()->id;
    }


    /**
     *  returns session('group'), if not set, sets it
     *
     * @return session(group)
     */
    public static function currentGroup()
    {
        if (!session()->has('group')) {
            session(['group' => group::find((groupMembers::where('userid', Auth::id())->first())->groupid)]);
        }
        return session('group');
    }


    /**
     * returns true/false if player is member of current group
     *
     * @param mixed $id passed id or name to check
     * @return boolean
     */
    public static function isMember($id = NULL, $groupid = NULL)
    {
        if (!$id) {
            $id =  Auth::id();
        }
        if (!$groupid) {
            $groupid = group::currentGroupId();
        }
        $id = (is_numeric($id)) ? $id : users::getIdFromName($id);
        $db = DB::select("SELECT u2.id as id FROM group_members gm INNER JOIN users u2 ON u2.id = gm.userid WHERE gm.groupid = " . $groupid . " AND u2.id = '$id'; ");
        return (count($db)) ? $db[0]->id : 0;
    }

    /**
     * returns true/false if logged in user is an admin of sessioned group
     *
     * @return boolean
     */
    public static function isAdmin()
    {
        $db = DB::select("SELECT gm.admin FROM group_members gm INNER JOIN users u2 ON u2.id = gm.userid WHERE gm.groupid = " . group::currentGroupId() . " AND u2.name = '" . Auth::user()->name . "';");
        return $db[0]->admin;
    }


    /**
     * returns all group members of sessioned group
     *
     * @return object
     */
    public static function getUserGroups()
    {
        return DB::select('SELECT g.* FROM `group` g INNER JOIN group_members gm ON gm.groupid = g.id WHERE gm.userid = ' . Auth::id());
    }
}
