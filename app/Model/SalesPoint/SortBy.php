<?php declare(strict_types = 1);

namespace Maisner\App\Model\SalesPoint;


use Maisner\App\Model\Utils\GpsCoordinates;
use MyCLabs\Enum\Enum;

class SortBy extends Enum {

	/** @var string */
	public const ID_ASC = 'id_asc';

	/** @var string */
	public const ID_DESC = 'id_desc';

	/** @var string */
	public const DISTANCE_ASC = 'distance_asc';

	/** @var string */
	public const DISTANCE_DESC = 'distance_desc';

	protected ?GpsCoordinates $actualGps;

	public function setActualGps(GpsCoordinates $gps): void {
		$this->actualGps = $gps;
	}

	public function getActualGps(): ?GpsCoordinates {
		return $this->actualGps;
	}

	public function isDistanceSort(): bool {
		return $this->getValue() === self::DISTANCE_ASC || $this->getValue() === self::DISTANCE_DESC;
	}

}
