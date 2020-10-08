<?php

namespace App\Http\Middleware;

use App\group;
use Closure;

class firstTimeSetup
{
    /**
     * check if the user has any data to display if not redirct to area where they can set up
     * displayable data e.g. creat there first group or join a group
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!group::currentGroupId()) {
            return redirect('/newgroup');
        }
        return $next($request);
    }
}
