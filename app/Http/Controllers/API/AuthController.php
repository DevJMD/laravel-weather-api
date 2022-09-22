<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	/*
	 * Create a new AuthController instance.
	 *
	 * @return void
	 */
	public function __construct ()
	{
		$this->middleware('auth:api', [ 'except' => [ 'login' ] ]);
	}

	/**
	 * Get a JWT token via given credentials.
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function login (Request $request) : JsonResponse
	{
		$request->validate([
			'email' => 'required|email|max:128',
			'password' => 'required|string|min:8|max:128',
		]);

		$credentials = $request->only('email', 'password');

		if ( $token = auth()->guard('api')->attempt($credentials) ) {
			return $this->respondWithToken($token);
		}

		return response()->json([
			'status' => 'error',
			'message' => __('auth.failed'),
		], 401);
	}

	/**
	 * Log the user out (Invalidate the token)
	 *
	 * @return JsonResponse
	 */
	public function logout () : JsonResponse
	{
		auth()->guard('api')->logout();

		return response()->json([
			'status' => 'success',
			'message' => __('auth.signed-out'),
		]);
	}

	/**
	 * Refresh a token.
	 *
	 * @return JsonResponse
	 */
	public function refresh () : JsonResponse
	{
		return $this->respondWithToken(auth()->guard('api')->refresh());
	}

	/**
	 * Get the token array structure.
	 *
	 * @param string $token
	 *
	 * @return JsonResponse
	 */
	protected function respondWithToken ($token) : JsonResponse
	{
		return response()->json([
			'status' => 'success',
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => auth()->guard('api')->factory()->getTTL() * 60,
		]);
	}
}
