<?php

namespace Tests\Feature\API\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
	/**
	 * Test if a user can authenticate via the login endpoint.
	 *
	 * @return void
	 */
	public function test_user_can_login () : void
	{
		$user = \App\Models\User::factory(1)->create()->first();

		$response = $this->post(route('api.v1.auth.login', [
			'email' => $user->getAttribute('email'), 'password' => 'password',
		]));

		$response->assertStatus(200);
	}

	/**
	 * Test if a user can login via the API and get a valid access token.
	 *
	 * @return void
	 */
	public function test_user_can_login_and_receive_valid_access_token () : void
	{
		$user = \App\Models\User::factory(1)->create()->first();

		$response = $this->post(route('api.v1.auth.login', [
			'email' => $user->getAttribute('email'), 'password' => 'password',
		]));

		// Assert the response is valid (200 status code).
		$response->assertStatus(200);

		// Assert the response has an access token.
		$this->assertArrayHasKey('status', $response->json());
		$this->assertArrayHasKey('access_token', $response->json());
		$this->assertArrayHasKey('expires_in', $response->json());
		$this->assertArrayHasKey('token_type', $response->json());
	}

	/**
	 * Test if a user can successfully sign out via the API.
	 *
	 * @return void
	 */
	public function test_user_can_logout () : void
	{
		$user = \App\Models\User::factory(1)->create()->first();

		$loginResponse = $this->post(route('api.v1.auth.login', [
			'email' => $user->getAttribute('email'), 'password' => 'password',
		]));

		$loginResponse->assertStatus(200);

		$logoutResponse = $this->post(route('api.v1.auth.logout'));

		$logoutResponse->assertStatus(200);
	}

	/**
	 * Test if a user can successfully sign out via the API.
	 *
	 * @return void
	 */
	public function test_user_can_refresh_access_token () : void
	{
		$user = \App\Models\User::factory(1)->create()->first();

		$loginResponse = $this->post(route('api.v1.auth.login', [
			'email' => $user->getAttribute('email'), 'password' => 'password',
		]));

		// Assert the request was successful (status code 200).
		$loginResponse->assertStatus(200);

		// Assert the user is authenticated.
		$this->assertAuthenticatedAs($user);

		// Create a new token by refreshing the existing one.
		$refreshResponse = $this->post(route('api.v1.auth.refresh'));

		// Assert the request was successful (status code 200).
		$refreshResponse->assertStatus(200);

		// Assert there is a token in the response.
		$this->assertTrue($refreshResponse->json('access_token') !== null);

		// Assert the login and refresh tokens do not match.
		$this->assertNotEquals($loginResponse->json('access_token'), $refreshResponse->json('access_token'));
	}

	/**
	 * Test to ensure a user can login and get a valid access token.
	 *
	 * @return void
	 */
	public function test_user_can_refresh_access_token_and_receive_valid_refresh_token () : void
	{
		/**
		 * See Tests\TestCase for the extended actingAs method.
		 */
		$this->actingAs($user = \App\Models\User::factory(1)->create()->first());

		// Assert the user is authenticated.
		$this->assertAuthenticatedAs($user);

		// Create a new token by refreshing the existing one.
		$response = $this->post(route('api.v1.auth.refresh'));

		// Assert the response is valid (200 status code).
		$response->assertStatus(200);

		// Assert the response has an access token.
		$this->assertArrayHasKey('status', $response->json());
		$this->assertArrayHasKey('access_token', $response->json());
		$this->assertArrayHasKey('expires_in', $response->json());
		$this->assertArrayHasKey('token_type', $response->json());
	}

	/**
	 * Test to ensure a user can successfully sign out via the API endpoint.
	 *
	 * @return void
	 */
	public function test_user_can_access_authenticated_route_guard () : void
	{
		$user = \App\Models\User::factory(1)->create()->first();

		$loginResponse = $this->post(route('api.v1.auth.login', [
			'email' => $user->getAttribute('email'), 'password' => 'password',
		]));

		// Assert the request was successful (status code 200).
		$loginResponse->assertStatus(200);

		// Call an authenticated route.
		$authenticatedResponse = $this->get(route('api.v1.weather.current', [ 'query' => 'London' ]));

		// Assert the request was successful (status code 200).
		$authenticatedResponse->assertStatus(200);
	}

	/**
	 * Test to ensure a guest cannot visit an authenticated route.
	 *
	 * @return void
	 */
	public function test_guest_cannot_access_authenticated_route_guard () : void
	{
		// Call an authenticated route.
		$authenticatedResponse = $this->get(route('api.v1.weather.current', [ 'query' => 'London' ]));

		// Assert the request was successful (status code 200).
		$authenticatedResponse->assertStatus(401);
	}

	/**
	 * Test to ensure a guest cannot access the refresh token endpoint.
	 *
	 * @return void
	 */
	public function test_guest_cannot_refresh_access_token () : void
	{

		// Create a new token by refreshing the existing one.
		$refreshResponse = $this->post(route('api.v1.auth.refresh'));

		// Assert the request was successful (status code 200).
		$refreshResponse->assertStatus(401);
	}

	/**
	 * Test to ensure a user cannot access the logout endpoint.
	 *
	 * @return void
	 */
	public function test_guest_cannot_access_logout () : void
	{

		// Create a new token by refreshing the existing one.
		$refreshResponse = $this->post(route('api.v1.auth.logout'));

		// Assert the request was successful (status code 200).
		$refreshResponse->assertStatus(401);
	}
}
