<?php

namespace App\Providers\ThirdParty;

use App\Services\WeatherService;
use Illuminate\Support\ServiceProvider;

class OpenWeatherMapServiceProvider extends ServiceProvider
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
