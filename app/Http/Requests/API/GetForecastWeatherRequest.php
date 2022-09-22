<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class GetForecastWeatherRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() : bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules() : array
	{
		return [
			'date' => 'date_format:Y-m-d',
			'postcode' => 'min:3|max:8',
			'coordinates' => 'array',

			/**
			 * Regex to match any double/int value, whether in number or string format.
			 */
			'coordinates.lat' =>  'regex:/^\d+(\.\d{1,2})?$/',
			'coordinates.long' =>  'regex:/^\d+(\.\d{1,2})?$/',
		];
	}

	public function messages () : array
	{
		/**
		 * Merge valid messages with custom validation messaging.
		 */
		return array_merge(parent::messages(), [
			'coordinates.lat' => 'Latitude values must be of an integer of double value, in either string or number format.',
			'coordinates.long' => 'Longitude values must be of an integer of double value, in either string or number format.'
		]);
	}
}
