<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
* model for quiz:password_resets
*
*/
class passwordResets extends Model {

    public $timestamps = false;
    protected $table = 'password_resets';

}