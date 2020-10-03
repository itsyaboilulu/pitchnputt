<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for golf:password_resets
 * @param string $email
 * @param mixed $token
 * @param datetime $created_at Default(CURRENT_DATETIME)
 */
class passwordResets extends Model
{

    public $timestamps = false;
    protected $table = 'password_resets';
}
