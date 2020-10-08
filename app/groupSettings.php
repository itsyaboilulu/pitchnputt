<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * model for golf:group_settings
 *
 * @param int groupid CK
 * @param string name CK
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
        if (!isset($settings['join_code'])) {
            $settings['join_code'] = groupSettings::generateJoinCode();
            $set =  new groupSettings();
            $set->groupid = group::currentGroupId();
            $set->name = 'join_code';
            $set->value = $settings['join_code'];
            $set->save();
        }
        return $settings;
    }

    /**
     * generates a random unique join code for users to join groups with
     *
     * @return void
     */
    public static function generateJoinCode()
    {
        $name = group::currentGroup()->name;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';
        for ($i = 0; $i < 10; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        //very unliklly but check for dublicates
        if (count(groupSettings::where('value', $name . $password)->get()) != 0) {
            //iterate on self untile we get somthing unique
            return groupSettings::generateJoinCode();
        }
        return $name . $password;
    }
}
