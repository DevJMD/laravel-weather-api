<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Requests\API\GetCurrentWeatherRequest;
use App\Http\Requests\API\GetForecastWeatherRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
	/**
	 * Get the current weather for a given location.
	 *
	 * @throws Exception
	 */
	public function getCurrent (GetCurrentWeatherRequest $request) : JsonResponse
	{
		$response = weather()->current($request);

		return response()->json($response);
	}

	/**
	 * Get the forecasted weather for a given location.
	 *
	 * @throws Exception
	 */
	public function getForecast (GetForecastWeatherRequest $request) : JsonResponse
	{
		$response = weather()->forecast($request);

		return response()->json($response);
	}
}
