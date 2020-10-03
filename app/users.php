<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for quiz:users
 *
 */
class users extends Model
{

    public $timestamps = false;
    protected $table = 'users';

    /**
     * retruns a players name using given user id and context of group
     *
     * @param int $uid
     * @return string
     */
    public static function getNameFromId($uid)
    {
        return (users::find($uid))->name;
    }

    public static function getIdFromName($name)
    {
        return (users::where('name', $name)->first())->id;
    }
}
