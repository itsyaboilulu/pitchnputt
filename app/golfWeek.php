<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for golf:golf_week
 *
 * @param int $id PK
 * @param datetime $date default(CURRENT_DATETIME)
 * @param int $groupid
 * @param int $weeknumber
 * @param int $corseid
 */
class golfWeek extends Model
{

    public $timestamps = false;
    protected $table = 'golf_week';
}
