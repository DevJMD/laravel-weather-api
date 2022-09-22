<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
	/**
	 * A list of exception types with their corresponding custom log levels.
	 *
	 * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
	 */
	protected $levels = [
		//
	];

	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array<int, class-string<\Throwable>>
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed to the session on validation exceptions.
	 *
	 * @var array<int, string>
	 */
	protected $dontFlash = [
		'current_password',
		'password',
		'password_confirmation',
	];

	/**
	 * Handling the rendering of the exception.
	 * For this example, I'll simply use a "catch-all" clause and return a JSON response.
	 *
	 * @param           $request
	 * @param Throwable $e
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
	 */
	public function render ($request, Throwable $e)
	{
		if ( $e instanceof \Exception || $e instanceof \Error ) {
			return response()->json([
				'data' => [
					'status' => 'error',
					'code' => method_exists('getCode', $e) ? $e->getCode() : 401,
					'error' => [
						'message' => strlen($e->getMessage()) > 0 ? $e->getMessage() : 'Unauthorized.',
					],
				],
			], method_exists('getCode', $e) ? $e->getCode() : 401);
		}
	}

	/**
	 * Register the exception handling callbacks for the application.
	 *
	 * @return void
	 */
	public function register () : void
	{
		$this->renderable(function (\Exception $e, $request) {});
	}
}
