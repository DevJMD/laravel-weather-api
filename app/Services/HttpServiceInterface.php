<?php

namespace App\Services;

interface HttpServiceInterface
{
	/**
	 * @param string $url
	 * @param array  $parameters
	 *
	 * @return object
	 * @throws \Exception
	 */
	public function get (string $url, array $parameters = []) : object;
}
