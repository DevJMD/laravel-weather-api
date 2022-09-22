<?php

namespace App\Services\Weather;

enum EWeatherRequestType
{
	case Current;
	case Forecast;
}
