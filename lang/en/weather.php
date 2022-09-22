<?php

return [

	'api' => [
		'exceptions' => [
			'missing_configuration' => 'Weather API: Invalid Configuration. Ensure an API key and Base URL is configured.',
			'missing_api_key' => 'API key for service ":service" is not set. `WEATHER_API_PUBLIC_KEY` must be set, or not empty in your .env file.',
			'missing_base_key' => 'Base URL for service ":service" is not set. `WEATHER_API_BASE_URL` must be set, or not empty in your .env file.',
		],
	]
];
