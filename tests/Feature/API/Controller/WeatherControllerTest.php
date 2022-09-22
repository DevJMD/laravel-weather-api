<?php

namespace Tests\Feature\API\Controller;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WeatherControllerTest extends TestCase
{
	/**
	 * Test that the current weather endpoint returns a 200 status code.
	 *
	 * @return void
	 */
	public function test_get_current_weather_returns_200_status_code () : void
	{
		/**
		 * See Tests\TestCase for the extended actingAs method.
		 */
		$this->actingAs($user = \App\Models\User::factory(1)->create()->first());

		// Call the current weather endpoint.
		$response = $this->get(route('api.v1.weather.current', [ 'query' => 'London' ]));

		// Assert the endpoint returns a 200 status code.
		$response->assertStatus(200);
	}

	/**
	 * Test that the current weather endpoint returns a valid response.
	 *
	 * @return void
	 */
	public function test_get_current_weather_returns_valid_json_response () : void
	{
		/**
		 * See Tests\TestCase for the extended actingAs method.
		 */
		$this->actingAs($user = \App\Models\User::factory(1)->create()->first());

		// Assert the user is authenticated.
		$this->assertAuthenticatedAs($user);

		// Call the current weather endpoint.
		$response = $this->get(route('api.v1.weather.current', [ 'query' => 'London' ]));

		// Assert the endpoint returns a 200 status code.
		$response->assertStatus(200);

		// Assert the endpoint returns a valid array type.
		$this->assertIsArray($response->json());
	}

	/**
	 * Test that the forecasted weather endpoint returns a 200 status code.
	 *
	 * @return void
	 */
	public function test_get_forecasted_weather_returns_200_status_code () : void
	{
		/**
		 * See Tests\TestCase for the extended actingAs method.
		 */
		$this->actingAs($user = \App\Models\User::factory(1)->create()->first());

		// Call the current weather endpoint.
		$response = $this->get(route('api.v1.weather.forecast', [ 'query' => 'London' ]));

		// Assert the endpoint returns a 200 status code.
		$response->assertStatus(200);
	}

	/**
	 * Test that the forecasted weather endpoint returns a valid JSON response.
	 *
	 * @return void
	 */
	public function test_get_forecasted_weather_returns_valid_json_response () : void
	{
		/**
		 * See Tests\TestCase for the extended actingAs method.
		 */
		$this->actingAs($user = \App\Models\User::factory(1)->create()->first());

		// Assert the user is authenticated.
		$this->assertAuthenticatedAs($user);

		// Call the current weather endpoint.
		$response = $this->get(route('api.v1.weather.forecast', [ 'query' => 'London' ]));

		// Assert the endpoint returns a 200 status code.
		$response->assertStatus(200);

		// Assert the endpoint returns a valid array type.
		$this->assertIsArray($response->json());
	}
}
