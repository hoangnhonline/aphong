<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
class AccountAvai {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ( Auth::check() )
        {   
            if(Auth::user()->status == 2){
            	die("Tài khoản đã bị khóa.");
            }
            return $next($request);
        }		
	}

}
