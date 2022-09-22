<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JsonRequest
{
	/**
	 * Handle an incoming request.
	 *
	 * @param Request $request
	 * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 *
	 * @return JsonResponse|RedirectResponse
	 */
	public function handle (Request $request, Closure $next) : JsonResponse | RedirectResponse
	{
		/**
		 * This will ensure that the returned `Content-Type` is `application/hal+json`,
		 * and will return a JSON response for all requests.
		 */
		$request->headers->set('Accept', 'application/hal+json');

		/**
		 * Continue request as usual.
		 */
		return $next($request);
	}
}
