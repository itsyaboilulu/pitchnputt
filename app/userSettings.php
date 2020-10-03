<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for golf:user_settings
 *
 * @param int id PK
 * @param string name
 * @param mixed value
 */
class userSettings extends Model
{
    public $timestamps = false;
    protected $table = 'user_settings';
}
