<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for golf:group_members
 *
 * @param int $userid CK
 * @param int $groupid CK
 * @param bool $admin
 */
class groupMembers extends Model
{

    public $timestamps = false;
    protected $table = 'group_members';
}
