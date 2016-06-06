<?php namespace App\Http\Middleware;

use Closure, Request;
use Illuminate\Contracts\Auth\Guard;

class Authenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->auth->guest()) {
			if ($request->ajax()) {
				return response('Não autorizado.', 401);
			} else {
        /*
        if ( Request::is('admin') ) {
  				return redirect()->guest('admin/auth/login');

        } else {
         */
  				return redirect()->guest('auth/login');

        //}
			}
		}

		return $next($request);
	}

}
