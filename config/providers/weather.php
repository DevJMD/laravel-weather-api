<?php

/**
 * Configure multiple third-party API providers.
 *
 * This example uses:
 *    1. WeatherAPI (https://www.weatherapi.com)
 *    2. OpenWeatherMap (https://openweathermap.org)
 *
 * API keys can be obtained from the respective websites for free, for development purposes.
 *
 * The primary reason behind using multiple API providers is to provide a fallback in the event that a
 * service, or multiple providers, become non-responsive. This is a common practice in the industry.
 *
 * Obviously for this scenario, it is more to demonstrate the use of transforming API responses that are
 * considerably different from one another. As highlighting DRY and SOLID principles are desirable,
 * I thought this would make sense to demonstrate the use of two different APIs to achieve the same goal.
 *
 * To switch between APIs, change the value of the `WEATHER_API` key in the `.env` file.
 * Accepted values are `weather-api` and `open-weather-map`.
 */

use App\Services\Weather\EWeatherService;

return [

	/**
	 * The source of data to use.
	 * Defaults to enumeration of EWeatherService::WeatherAPI.
	 *
	 * Possible enum types:
	 *        [1] EWeatherService::WeatherAPI
	 *        [2] EWeatherService::OpenWeatherMap
	 */
	'source' => EWeatherService::WeatherAPI,

	/**
	 * Configure various third-party weather API providers.
	 * Their respective configs can be found in the `config/providers/third-party` directory.
	 */
	'api' => [

		'WeatherAPI' => [
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
		],

		'OpenWeatherMap' => [

			/**
			 * The base URL of the weather service.
			 */
			'base_url' => env('OPEN_WEATHER_MAP_BASE_URL', 'https://api.openweathermap.org/data/2.5/weather'),

			/**
			 * The service API key used to authenticate requests.
			 */
			'api_key' => env('OPEN_WEATHER_MAP_KEY'),

			/**
			 * The default location to use when no location is provided.
			 */
			'default_location' => env('WEATHER_DEFAULT_LOCATION', 'London,UK'),

		],

	],

];
