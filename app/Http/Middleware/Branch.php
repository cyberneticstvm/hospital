<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;

class Branch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next){
        $user_id = Auth::user()->id;
        $branches = DB::table('branches')->leftJoin('user_branches', 'branches.id', '=', 'user_branches.branch_id')->select('branches.id', 'branches.branch_name')->where('user_branches.user_id', '=', $user_id)->get();
        if(session()->get('branch') > 0):
            return $next($request);
        endif;
        return redirect()->route('error')->with('error','Denied!!! You dont have an active branch assigned. Please logout and login again to choose the branch.');
    }
}
