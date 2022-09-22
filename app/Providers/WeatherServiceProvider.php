<?php

namespace App\Providers;

use App\Services\WeatherService;
use Illuminate\Support\ServiceProvider;

class WeatherServiceProvider extends ServiceProvider
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
		$this->app->bind(WeatherService::class, function () {
			return $this->app->make(WeatherService::class);
		});
	}
}
