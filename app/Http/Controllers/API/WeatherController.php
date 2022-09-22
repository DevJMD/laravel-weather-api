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
		/**
		 * Debug.
		 */
		Log::info('Request data: ' . json_encode($request->all()));
		Log::info('Query Passed in: ' . $request->request->get('query'));

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
		/**
		 * Debug.
		 */
		Log::info('Request data: ' . json_encode($request->all()));
		Log::info('Query Passed in: ' . $request->request->get('query'));

		$response = weather()->forecast($request);


		return response()->json($response);
	}
}
