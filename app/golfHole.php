<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for golf:golf_hole
 *
 * @param int $id PK
 * @param int $corseid
 * @param int $range
 * @param int $hole
 */
class golfHole extends Model
{

    public $timestamps = false;
    protected $table = 'golf_hole';
}
