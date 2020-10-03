<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for golf:users
 *
 * @param int $id PK
 * @param string $name
 * @param string $email
 * @param datetime $email_verified_at
 * @param mixed $remember_token
 * @param datetime $created_at Default(CURRENT_DATETIME)
 * @param datetime $updated_at Default(CURRENT_DATETIME)
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


    /**
     * Undocumented function
     *
     * @param string $name username
     * @return int
     */
    public static function getIdFromName($name)
    {
        return (users::where('name', $name)->first())->id;
    }
}
