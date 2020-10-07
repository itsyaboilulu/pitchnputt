<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for golf:golf_week
 *
 * @param int $uid PK
 * @param datetime created default(CURRENT_TIMESTAMP)
 */
class usersTemp extends Model
{
    public $timestamps = false;
    protected $table = 'users_temp';
}
