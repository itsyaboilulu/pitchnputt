<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for golf:golf_score
 *
 * @param int $id PK
 * @param int $week
 * @param int $hole
 * @param int $user
 * @param int $score
 * @param int $groupid
 *
 */
class golfScore extends Model
{

    public $timestamps = false;
    protected $table = 'golf_score';
}
