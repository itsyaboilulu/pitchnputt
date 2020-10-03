<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for quiz:group_settings
 *
 */
class groupSettings extends Model
{

    public $timestamps = false;
    protected $table = 'group_settings';


    public static function getGroupSettings($gid = 0)
    {
        if (!$gid) {
            $gid = group::currentGroupId();
        }
        foreach (groupSettings::where('groupid', $gid)->get() as $setting) {
            $settings[$setting->name] = $setting->value;
        }
        return $settings;
    }
}
