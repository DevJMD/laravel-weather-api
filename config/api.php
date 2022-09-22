<?php

return [

	/**
	 * The API major version number prefixed with `v`.
	 */
	'version' => 'v' . env('API_VERSION', 1),

	/**
	 * The url prefix, i.e., the first segment(s) of the url.
	 *
	 * @see     https://laravel.com/docs/9.x/routing#route-group-prefixes
	 * @example Route::prefix('api/v1')->group(static function () {});
	 *          Usage: `https://weather.com/api/v1/my/url/path`
	 */
	'url_prefix' => env('API_URL_PREFIX', 'api/' . config('api.version')),

	/**
	 * The route prefix, i.e., `api.v1.route-name`.
	 *
	 * @see     https://laravel.com/docs/9.x/routing#route-group-name-prefixes
	 * @example Route::name('api.v1.')->group(static function () {});
	 *          Usage: `route('api.v1.<RouteName>')`
	 */
	'route_prefix' => env('API_ROUTE_PREFIX', 'api.' . config('api.url_prefix')),

	/**
	 * The middleware to apply to all API routes by default.
	 *
	 * @see     https://laravel.com/docs/9.x/routing#route-group-middleware
	 * @example Route::middleware(['api', 'api:auth'])->group(static function () {});
	 */
	'middleware' => explode(',', env('API_MIDDLEWARE', 'api,api:auth')),

	'routes' => base_path('routes/api.php'),
];
