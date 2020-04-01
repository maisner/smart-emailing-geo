<?php declare(strict_types = 1);

namespace Maisner\App\Model\Utils;

use Nette\SmartObject;

class GpsCoordinates implements \JsonSerializable {

	use SmartObject;

	private float $latitude;

	private float $longitude;

	public function __construct(float $latitude, float $longitude) {
		$this->latitude = $latitude;
		$this->longitude = $longitude;
	}

	public function getLatitude(): float {
		return $this->latitude;
	}

	public function getLongitude(): float {
		return $this->longitude;
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize(): array {
		return [
			'latitude'  => $this->getLatitude(),
			'longitude' => $this->getLongitude()
		];
	}
}
