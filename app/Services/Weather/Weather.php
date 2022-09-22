<?php

namespace App\Services\Weather;

use Exception;
use Carbon\Carbon;
use App\Exceptions\WeatherException;
use App\Http\Requests\API\GetCurrentWeatherRequest;
use App\Http\Requests\API\GetForecastWeatherRequest;
use App\Transforms\HypertextApplicationLinkTransform;
use App\Transforms\WeatherDataTransform;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\MessageInterface;

abstract class Weather implements WeatherInterface
{
	/**
	 * The default third-party API service to use.
	 *
	 * @var EWeatherService $service
	 */
	public EWeatherService $service;

	/**
	 * The API key to use for authenticated third-party providers.
	 *
	 * @var string|null $apiKey
	 */
	protected string | null $apiKey;

	/**
	 * Conversion constants.
	 */
	private const METRIC_TEMPERATURE = 'celsius';
	private const IMPERIAL_TEMPERATURE = 'fahrenheit';

	/**
	 * Get the current weather for a given location.
	 *
	 * @param GetForecastWeatherRequest $request
	 *
	 * @return array|object
	 * @throws Exception
	 */
	abstract public function forecast (GetForecastWeatherRequest $request) : array | object;

	/**
	 * Get the current weather for a given location.
	 *
	 * @param GetCurrentWeatherRequest $request
	 *
	 * @return array|object
	 * @throws Exception
	 */
	abstract public function current (GetCurrentWeatherRequest $request) : array | object;

	/**
	 * Make the API call to the selected service.
	 *
	 * @throws WeatherException
	 */
	protected function makeApiCall (EWeatherRequestType $requestType, mixed $request) : object | array
	{
		/**
		 * @var MessageInterface $data
		 */
		try {
			$response = match ( $requestType ) {
				// Get the current weather for a given location.
				EWeatherRequestType::Current  => $this->getCurrentWeather($request),

				// Get the forecast for a given location.
				EWeatherRequestType::Forecast => $this->getForecastedWeather($request),
			};
		} catch ( WeatherException | GuzzleException | NotFoundExceptionInterface | ContainerExceptionInterface $e ) {
			throw new WeatherException($e->getMessage());
		}

		/**
		 * Let's need to check if the response is successful.
		 *
		 * @var object $data The response data.
		 */
		if ( $response->status === 200 ) {
			$data = $response->data;

			if ( array_key_exists('error', $data) ) {
				throw new WeatherException($data['error']['message']);
			}

			/**
			 * Handle forecasted weather data.
			 */
			if ( $requestType === EWeatherRequestType::Forecast ) {
				$data = WeatherDataTransform::transform($this->service, $requestType, $data, $request);
			}

			/**
			 * Handle current weather data.
			 */
			if ( $requestType === EWeatherRequestType::Current ) {
				$data = WeatherDataTransform::transform($this->service, $requestType, $data, $request);
			}

			$data['error'] = [];

			return HypertextApplicationLinkTransform::transformRequest($request, $data);
		}

		//
		// @todo: handle exceptions/API status codes.
		//

		throw new WeatherException('The weather service is currently unavailable.');
	}

	/**
	 * Build the minimum query parameters for the API request.
	 * API key is required to authenticate the request, so we
	 * will merge the key with the query. If the incoming query
	 * is a string, then we'll assign the string to $payload
	 * directly. If it's an array, then we'll merge the two arrays
	 * together.
	 *
	 *
	 * @param array $request
	 *
	 * @return array
	 */
	protected function createQueryPayload (mixed $request) : array
	{
		$payload = [];

		$request = $request->all();

		/**
		 * Sanitize $request array. This is what we'll break down to assign specific API
		 * query parameters. This is effectively transforming "our" API
		 * values into an acceptable payload that various weather APIs can accept.
		 *
		 * As I'm keeping it simple, we'll only accept a JSON payload that takes a key of
		 * `query`. The value of this key is what we'll use to fetch API data.
		 */

		if ( array_key_exists('query', $request) ) {
			$payload['q'] = $request['query'];
		}

		/**
		 * Assign `days` to the payload. Days wll drill down the forecasted weather days.
		 */
		if ( array_key_exists('days', $request) ) {
			$payload['days'] = $request['days'];
		}

		/**
		 * Assign `days` to the payload. Days wll drill down the forecasted weather by an exact date.
		 */
		if ( array_key_exists('date', $request) ) {
			$payload['dt'] = $request['date'];
		}

		/**
		 * Merge the API key required to make authenticated API requests.
		 */
		$payload['key'] = $this->apiKey;

		/**
		 * Debug.
		 */
		Log::info('Payload: ' . json_encode($payload));

		return $payload;
	}

	/**
	 * Get the configuration for the specified API service.
	 *
	 * @param EWeatherService $source
	 *
	 * @return array
	 * @throws Exception
	 */
	protected function getServiceConfiguration (EWeatherService $source) : array
	{
		$config = config('providers.weather.api.' . $source->name);

		if ( ! $config ) {
			throw new Exception('Invalid WeatherService source. "' . $source->name . '" service does not exist.');
		}

		return $config;
	}

	/**
	 * @param mixed $request
	 *
	 * @return object
	 * @throws ContainerExceptionInterface|GuzzleException|NotFoundExceptionInterface
	 */
	protected function getCurrentWeather (mixed $request) : object
	{
		$url = $this->baseUrl . '/current.json';

		/**
		 * Send GET request to the API.
		 */
		return http()->get($url, [ 'query' => $this->createQueryPayload($request) ]);
	}

	/**
	 * @param mixed $request
	 *
	 * @return object
	 * @throws ContainerExceptionInterface|GuzzleException|NotFoundExceptionInterface|WeatherException
	 */
	protected function getForecastedWeather (mixed $request) : object
	{
		/**
		 * Handle specific date for forecasted weather.
		 */
		if ( $request->date ) {
			try {
				// Format date.
				$date = Carbon::make($request->date);

				// Add the data to the request query. We'll use it later when sending it to the API.
				$request->query->add([ 'date' => $date->format('Y-m-d') ]);
			} catch ( Exception $e ) {
				throw new WeatherException('Invalid date format. Please use the following format: YYYY-MM-DD');
			}
		}

		/**
		 * Handle forecasted weather endpoint.
		 * Much simpler/cleaner than the OpenWeatherMap API.
		 */
		$url = $this->baseUrl . '/forecast.json';

		/**
		 * Send GET request to the API.
		 */
		return http()->get($url, [ 'query' => $this->createQueryPayload($request) ]);
	}
}
