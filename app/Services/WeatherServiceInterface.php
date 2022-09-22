<?php

namespace App\Services;

use App\Exceptions\WeatherException;
use App\Http\Requests\API\GetCurrentWeatherRequest;
use App\Http\Requests\API\GetForecastWeatherRequest;

interface WeatherServiceInterface
{
	/**
	 * Get the current weather for a given location.
	 *
	 * @param GetForecastWeatherRequest $request
	 *
	 * @return array|object
	 * @throws WeatherException
	 */
	public function forecast (GetForecastWeatherRequest $request) : array | object;

	/**
	 * Get the forecasted weather for a given location.
	 *
	 * @param GetCurrentWeatherRequest $request
	 *
	 * @return array|object
	 * @throws WeatherException
	 */
	public function current (GetCurrentWeatherRequest $request) : array | object;
}
