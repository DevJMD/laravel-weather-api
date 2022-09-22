<?php

namespace App\Providers;

use App\Services\HttpService;
use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
	/**
	 * Register providers.
	 *
	 * @return void
	 */
	public function register () : void
	{
		//
	}

	/**
	 * Bootstrap providers.
	 *
	 * @return void
	 */
	public function boot () : void
	{
		$this->app->bind(HttpService::class, function () {
			return $this->app->make(HttpService::class);
		});
	}
}
