<?php declare(strict_types = 1);

namespace Maisner\App\Model\Geolocation;

use Maisner\App\Model\Utils\GpsCoordinates;
use Maisner\App\Model\Utils\IP;

interface GeolocationServiceInterface {

	public function getGpsByIp(IP $ip): GpsCoordinates;
}
