<?php

namespace App\Services;

use App\Exceptions\WeatherException;
use App\Http\Requests\API\GetCurrentWeatherRequest;
use App\Http\Requests\API\GetForecastWeatherRequest;
use App\Services\Weather\EWeatherService;
use Exception;
use App\Services\Weather\EWeatherRequestType;
use App\Services\Weather\Weather;
use Illuminate\Http\Request;

/**
 * The WeatherService class to compliment the WeatherServiceProvider.
 * We'll extend an abstraction class and implement the IWeatherService interface.
 *
 * @class WeatherService
 */
class WeatherService extends Weather
{

	/**
	 * The cache in order to cache responses.
	 *
	 * @var string $cache
	 */
	protected mixed $cache;

	/**
	 * The API key to use for authenticated third-party providers.
	 *
	 * @var string|null $apiKey
	 */
	protected string | null $apiKey;

	/**
	 * The base URL for the third-party API service.
	 *
	 * @var string|null $baseUrl
	 */
	protected string | null $baseUrl;

	/**
	 * The default third-party API service to use.
	 *
	 * @var EWeatherService $service
	 */
	public EWeatherService $service;

	/**
	 * Bootstrap API wrapper.
	 *
	 * @throws Exception
	 */
	public function __construct ()
	{
		$this->service = config('providers.weather.source');
		$this->cache = cache();

		/**
		 * Configure the required WeatherAPI configuration.
		 *
		 * 1. Get the API key from config.
		 * 2. Get the base URL from config.
		 */
		$source = $this->getServiceConfiguration($this->service);

		$this->apiKey = $source['api_key'];   // [1]
		$this->baseUrl = $source['base_url']; // [2]

		if ( ! $this->apiKey ) {
			// See translations in `lang/en/weather.php`.
			// @see https://laravel.com/docs/9.x/localization#retrieving-translation-strings
			throw new \Error(__('weather.api.exceptions.missing_api_key', [ 'service' => $this->service->name ]));
		}

		if ( ! $this->baseUrl ) {
			// See translations in `lang/en/weather.php`.
			// @see https://laravel.com/docs/9.x/localization#retrieving-translation-strings
			throw new \Error(__('weather.api.exceptions.missing_base_url', [ 'service' => $this->service->name ]));
		}
	}

	/**
	 * Get the current weather for a given location.
	 *
	 * @param GetCurrentWeatherRequest $request
	 *
	 * @return array|object
	 * @throws WeatherException
	 */
	public function current (GetCurrentWeatherRequest $request) : array | object
	{
		return $this->makeApiCall(EWeatherRequestType::Current, $request);
	}

	/**
	 * Get the forecasted weather for a given location.
	 *
	 * @param GetForecastWeatherRequest $request
	 *
	 * @return array|object
	 * @throws WeatherException
	 */
	public function forecast (GetForecastWeatherRequest $request) : array | object
	{
		return $this->makeApiCall(EWeatherRequestType::Forecast, $request);
	}
}
