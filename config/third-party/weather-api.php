<?php

return [

	/**
	 * The base URL of the weather service.
	 */
	'base_url' => env('WEATHER_API_BASE_URL', 'https://api.weatherapi.com/v1'),

	/**
	 * The service API key used to authenticate requests.
	 */
	'api_key' => env('WEATHER_API_KEY'),

	/**
	 * The default location to use when no location is provided.
	 */
	'default_location' => env('WEATHER_DEFAULT_LOCATION', 'London,UK'),
];
