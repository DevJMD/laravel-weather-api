<?php

namespace App\Transforms;

use Illuminate\Http\Request;

/**
 * @ref https://datatracker.ietf.org/doc/html/draft-kelly-json-hal-08
 */
class HypertextApplicationLinkTransform
{
	/**
	 * Transform the request into a HAL response.
	 *
	 * @param Request $request
	 * @param array   $data
	 *
	 * @return array[]
	 */
	public static function transformRequest (Request $request, array $data) : array
	{
		$parent = [];

		/**
		 * Set parent links.
		 */
		$parent['_links'] = [
			'self' => [
				'href' => '/' . $request->path(),
			],
		];

		$parent['_embedded'] = [
			'forecast_day' => [],
		];

		/**
		 * Running out of time - just gonna make sure it's HAL compliant for now.
		 */

		/**
		 * If we have a forecast, we need to add the forecast days, and add links for hourly breakdowns.
		 */
		if ( array_key_exists('forecast', $data) ) {
			/**
			 * Loop through each forecast day and add the links.
			 */
			foreach ( $data['forecast'] as $forecast ) {
				if ( array_key_exists('date', $forecast) ) {
					$parent['_embedded']['forecast_day'][] = [

						// [1] Dynamically link to both day and hourly breakdown.
						// [2] Add the forecast date.
						// [3] Add the updated_at time for the forecast.

						'_links' => [
							'self' => [
								'href' => route('api.v1.weather.forecast.date', [ 'date' => $forecast['date'] ], false), // [1]
							],
							'hourly' => [
								'href' => route('api.v1.weather.forecast.date.hourly', [ 'date' => $forecast['date'] ], false), // [1]
							],
						],
						'date' => $forecast['date'],             // [2]
						'updated_at' => $forecast['updated_at'], // [3]
					];

					$forecast['_links'] = [
						'self' => [
							'href' => route('api.v1.weather.forecast.date', [ 'date' => $forecast['date'] ], false), // [1]
						],
						'hourly' => [
							'href' => route('api.v1.weather.forecast.date.hourly', [ 'date' => $forecast['date'] ], false), // [1]
						],
					];
				}
			}
		}

		return array_merge($parent, [ 'data' => $data ]);
	}
}
