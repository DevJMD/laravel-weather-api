<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
	/**
	 * Handle an incoming request.
	 *
	 * @param Request     $request
	 * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @param string|null ...$guards
	 *
	 * @return Response|RedirectResponse
	 */
	public function handle (Request $request, Closure $next, ...$guards) : JsonResponse | RedirectResponse
	{
		$guards = empty($guards) ? [ null ] : $guards;

		foreach ( $guards as $guard ) {
			if ( Auth::guard($guard)->check() ) {
				return response()->json([
					'status' => 'success',
					'message' => 'Already authenticated.',
					'access_token' => $request->bearerToken(),
				]);
			}
		}

		return $next($request);
	}
}
