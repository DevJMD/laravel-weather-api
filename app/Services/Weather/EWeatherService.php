<?php

namespace App\Services\Weather;

enum EWeatherService
{
	case WeatherAPI;
	case OpenWeatherMap;
}
