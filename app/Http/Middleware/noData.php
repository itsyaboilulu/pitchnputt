<?php

namespace App\Http\Middleware;

use App\golfWeek;
use App\group;
use Closure;

class noData
{
    /**
     * check if there is no usable data and redirect to settings page to allow user
     * to add data
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (count(golfWeek::where('groupid', group::currentGroupId())->get()) == 0) {
            return redirect('/lobby');
        }
        return $next($request);
    }
}
