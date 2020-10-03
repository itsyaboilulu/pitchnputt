<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for golf:group_settings
 *
 * @param int groupid PK
 * @param string name
 * @param mixed value
 */
class groupSettings extends Model
{

    public $timestamps = false;
    protected $table = 'group_settings';


    /**
     * gets the setting for a given group
     *
     * @param integer $gid group id
     * @return array( setting => value)
     */
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
