<?php

if ( ! function_exists('weather') ) {

	/**
	 * The singleton pattern is used to ensure only one instance of the service is created.
	 * This is a helper function to access that instance of the WeatherService class.
	 *
	 * @return \App\Services\WeatherService
	 */
	function weather () : \App\Services\WeatherService
	{
		return app(\App\Services\WeatherService::class);
	}

	/**
	 * @return \App\Services\HttpService
	 */
	function http () : \App\Services\HttpService
	{
		return app(\App\Services\HttpService::class);
	}
}
