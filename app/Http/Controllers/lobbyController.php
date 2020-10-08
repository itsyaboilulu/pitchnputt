<?php

namespace App\Http\Controllers;

use App\group;
use App\groupMembers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class lobbyController extends Controller
{
    /**
     * load middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Dispaly waiting lobby, for groups that havent added any data yet, if admin redircets to the settinsg page to add data
     *
     * @return mixed
     */
    public function pageLobby()
    {
        try {
            if (group::isAdmin()) {
                return redirect('/groupsettings');
            }
            return view('lobby', array(
                'members' => DB::select('SELECT u.name,gm.admin FROM users u INNER JOIN group_members gm ON gm.userid = u.id WHERE gm.groupid = ' . group::currentGroupId()),
            ));
        } catch (Exception $e) {
            //stops removed users getting looped on the lobby screen
            session()->forget('group');
            return redirect('/');
        }
    }
}
