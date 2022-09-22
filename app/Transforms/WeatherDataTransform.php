<?php

namespace App\Transforms;

use App\Services\Weather\EWeatherRequestType;
use App\Services\Weather\EWeatherService;

class WeatherDataTransform
{
	const CELSIUS_MAX = 'celsius_max';
	const CELSIUS_MIN = 'celsius_min';

	/**
	 * Handle data transformations.
	 *
	 * @param EWeatherService     $service
	 * @param EWeatherRequestType $requestType
	 * @param array               $data
	 * @param                     $request
	 *
	 * @return array
	 */
	public static function transform (EWeatherService $service, EWeatherRequestType $requestType, array $data, $request) : array
	{
		if ( $requestType === EWeatherRequestType::Current ) {
			return self::extractCurrentWeather($service, $data, $request);
		}

		if ( $requestType === EWeatherRequestType::Forecast ) {
			return self::extractForecastedWeather($service, $data, $request);
		}

		return $data;
	}

	/**
	 * Ignore this. Had to put it out to finish it in a respectable time.
	 * Not reflective of everything else, but realistically, I would have liked to
	 * have done this in a more elegant way, mapping API data to a predefined API structure.
	 *
	 * @param EWeatherService $service
	 * @param array           $data
	 * @param                 $request
	 *
	 * @return array
	 */
	protected static function extractCurrentWeather (EWeatherService $service, array $data, $request) : array
	{
		if ( $service === EWeatherService::WeatherAPI ) {
			return [
				'city' => $data['location']['name'],
				'country' => $data['location']['country'],
				'current' => [
					'temperature' => $data['current']['temp_c'],
					'condition' => $data['current']['condition']['text'],
					'condition_icon' => $data['current']['condition']['icon'],
					'wind_speed' => $data['current']['wind_kph'],
					'wind_direction' => $data['current']['wind_dir'],
					'humidity' => $data['current']['humidity'],
					'feels_like' => $data['current']['feelslike_c'],
					'uv' => $data['current']['uv'],
					'precipitation' => $data['current']['precip_mm'],
					'pressure' => $data['current']['pressure_mb'],
				],
			];
		}

		return $data;
	}

	/**
	 * Ignore this. Had to put it out to finish it in a respectable time.
	 * Not reflective of everything else, but realistically, I would have liked to
	 * have done this in a more elegant way, mapping API data to a predefined API structure.
	 *
	 * @param EWeatherService $service
	 * @param array           $data
	 * @param                 $request
	 *
	 * @return array
	 */
	protected static function extractForecastedWeather (EWeatherService $service, array $data, $request) : array
	{
		/**
		 * Manage WeatherAPI
		 */
		if ( $service === EWeatherService::WeatherAPI ) {
			if ( array_key_exists('forecast', $data) && array_key_exists('forecastday', $data['forecast']) ) {
				$forecastDays = $data['forecast']['forecastday'];
			} else {
				$forecastDays = $data['forecastday'];
			}

			$formattedForecast = [];
			$breakdownHourly = [];

			foreach ( $forecastDays as $forecast ) {
				if ( $request->segment(5) === 'hourly' ) {
					$formattedHourly = [];

					foreach ( $forecast['hour'] as $hour ) {
						$formattedHourly[] = [
							'time' => $hour['time'],
							'temperature' => $hour['temp_c'],
							'temperature_min' => $hour['temp_c'],
							'temperature_max' => $hour['temp_c'],
							'wind_speed' => $hour['wind_mph'],
							'wind_degree' => $hour['wind_degree'],
							'wind_direction' => $hour['wind_dir'],
							'pressure' => $hour['pressure_mb'],
							'precipitation' => $hour['precip_mm'],
							'humidity' => $hour['humidity'],
							'cloud' => $hour['cloud'],
							'temperature_feelslike' => $hour['feelslike_c'],
							'windchill' => $hour['windchill_c'],
							'dewpoint' => $hour['dewpoint_c'],
							'will_rain' => $hour['will_it_rain'],
							'will_snow' => $hour['will_it_snow'],
							'chance_of_rain' => $hour['chance_of_rain'],
							'chance_of_snow' => $hour['chance_of_snow'],
							'visiblity' => $hour['vis_miles'],
							'gust_speed' => $hour['gust_mph'],
						];
					}

					$breakdownHourly[] = $formattedHourly;
				}

				$formattedForecast[] = [
					'date' => $forecast['date'],
					'temperature_average' => $forecast['day']['avgtemp_c'],
					'temperature_min' => $forecast['day']['mintemp_c'],
					'temperature_max' => $forecast['day']['maxtemp_c'],
					'wind_speed_max' => $forecast['day']['maxwind_mph'],
					'precipitation' => $forecast['day']['totalprecip_mm'],
					'humidity' => $forecast['day']['avghumidity'],
					'daily_chance_of_rain' => $forecast['day']['daily_chance_of_rain'],
					'daily_chance_of_snow' => $forecast['day']['daily_chance_of_snow'],
					'daily_will_it_rain' => $forecast['day']['daily_will_it_rain'],
					'daily_will_it_snow' => $forecast['day']['daily_will_it_snow'],
					'updated_at' => $data['current']['last_updated'],
				];
			}

			/**
			 * Filter a specific forecast by date.
			 */
			if ( $request->has('date') ) {
				$filteredDateForecast = array_filter($formattedForecast, function ($day) use ($request) {
					if ( array_key_exists('date', $day) ) {
						return $day['date'] === $request->get('date');
					}

					return false;
				});

				if ( count($filteredDateForecast) === 1 ) {
					$formattedForecast = $filteredDateForecast;
				}
			}

			$dataToReturn = [
				'city' => $data['location']['name'],
				'country' => $data['location']['country'],
				'forecast' => $formattedForecast,
			];

			if ( ! empty($breakdownHourly) ) {
				$dataToReturn['hourly_breakdown'] = $breakdownHourly;
			}

			return $dataToReturn;
		}

		return $data;
	}
}
