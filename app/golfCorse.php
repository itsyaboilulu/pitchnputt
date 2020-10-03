<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for quiz:golf_corse
 *
 * @param int $id PK
 * @param string $name
 * @param int $groupid
 */
class golfCorse extends Model
{

    public $timestamps = false;
    protected $table = 'golf_corse';
}
