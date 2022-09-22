<?php

namespace App\Exceptions;

use Exception;

class WeatherException extends Exception
{
	public function __construct (string $message = '', int $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public function render ()
	{
		return response()->json([
			'error' => $this->getMessage(),
		]);
	}

	public function report ()
	{
		//
	}

	public function withResponse ($request, $response)
	{
		//
	}

	public function renderForConsole ($output)
	{
		//
	}
}
