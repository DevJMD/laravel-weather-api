<?php

namespace App\Services\Weather;

use App\Http\Requests\API\GetCurrentWeatherRequest;
use App\Http\Requests\API\GetForecastWeatherRequest;
use Illuminate\Http\Request;

interface WeatherInterface
{
	/**
	 * Get the current weather for a given location.
	 *
	 * @param GetCurrentWeatherRequest $request
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function current (GetCurrentWeatherRequest $request) : mixed;

	/**
	 * Get the forecasted weather for a given location.
	 *
	 * @param GetForecastWeatherRequest $request
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function forecast (GetForecastWeatherRequest $request) : mixed;
}
