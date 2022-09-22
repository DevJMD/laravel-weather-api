<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;
	use RefreshDatabase;

	public function setUp () : void
	{
		parent::setUp();
	}

	/**
	 * Set the currently logged in user for the application.
	 *
	 * @param Authenticatable $user
	 * @param string|null     $driver
	 *
	 * @return $this
	 */
	public function actingAs (Authenticatable $user, $driver = null) : static
	{
		$token = JWTAuth::fromUser($user);
		$this->withHeader('Authorization', 'Bearer ' . $token);

		parent::actingAs($user);

		return $this;
	}
}
