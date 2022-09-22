<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;

class HttpService implements HttpServiceInterface
{
	/**
	 * Guzzle HTTP client to make HTTP verb requests.
	 *
	 * @var Client $client
	 */
	protected Client $client;

	public function __construct ()
	{
		$this->client = app(Client::class);
	}

	/**
	 * @param string $url
	 * @param array  $parameters
	 *
	 * @return object
	 * @throws ContainerExceptionInterface|GuzzleException|NotFoundExceptionInterface|InvalidArgumentException
	 */
	public function get (string $url, array $parameters = []) : object
	{
		/**
		 * Create a unique request key to use for caching.
		 */
		$key = md5($url . json_encode($parameters));

		/**
		 * Check if the request has been cached.
		 */
		if (cache()->has($key)) {
			$cachedResponse = cache()->get($key);
		}

		/**
		 * If the request has been cached, return the cached response.
		 */
		if (isset($cachedResponse) && $cachedResponse) {
			return $cachedResponse;
		} else {
			/**
			 * If the request has not been cached, make the request and cache the response.
			 */
			$response = $this->client->get($url, $parameters);

			/**
			 * Cache the response for 15 minutes (interval update times).
			 * Logically, it would make sense to take the updated time from the response and cache it for that amount of time.
			 * However, time...
			 */
			$cachedResponse = cache()->remember($key, 15, function () use ($response) {
				// Store the response in the cache.
				// We'll keep the status code and obviously, data.
				$status = $response->getStatusCode();
				$data = json_decode($response->getBody()->getContents(), true);

				return (object) ['status' => $status, 'data' => $data];
			});
		}

		return $cachedResponse;
	}
}
