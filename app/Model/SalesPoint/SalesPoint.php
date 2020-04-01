<?php declare(strict_types = 1);

namespace Maisner\App\Model\SalesPoint;

use Maisner\App\Model\EntityInterface;
use Maisner\App\Model\Utils\GpsCoordinates;

class SalesPoint implements EntityInterface, \JsonSerializable {

	private int $id;

	private string $externalId;

	private Type $type;

	private string $name;

	private OpeningHoursCollection $openingHours;

	private GpsCoordinates $gpsCoordinates;

	private int $services;

	private int $paymentMethods;

	private ?string $address;

	private ?string $remarks;

	private ?string $link;

	public function __construct(
		int $id,
		string $externalId,
		Type $type,
		string $name,
		OpeningHoursCollection $openingHours,
		GpsCoordinates $gpsCoordinates,
		int $services,
		int $paymentMethods,
		?string $address,
		?string $remarks,
		?string $link
	) {
		$this->id = $id;
		$this->externalId = $externalId;
		$this->type = $type;
		$this->name = $name;
		$this->openingHours = $openingHours;
		$this->gpsCoordinates = $gpsCoordinates;
		$this->services = $services;
		$this->paymentMethods = $paymentMethods;
		$this->address = $address;
		$this->remarks = $remarks;
		$this->link = $link;
	}

	public function getId(): int {
		return $this->id;
	}

	public function getExternalId(): string {
		return $this->externalId;
	}

	public function getType(): Type {
		return $this->type;
	}

	public function getName(): string {
		return $this->name;
	}

	public function getOpeningHours(): OpeningHoursCollection {
		return $this->openingHours;
	}

	public function getGpsCoordinates(): GpsCoordinates {
		return $this->gpsCoordinates;
	}

	public function getServices(): int {
		return $this->services;
	}

	public function getPaymentMethods(): int {
		return $this->paymentMethods;
	}

	public function getAddress(): ?string {
		return $this->address;
	}

	public function getRemarks(): ?string {
		return $this->remarks;
	}

	public function getLink(): ?string {
		return $this->link;
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize(): array {
		return [
			'id'              => $this->getId(),
			'external_id'     => $this->getExternalId(),
			'type'            => $this->getType()->getValue(),
			'name'            => $this->getName(),
			'opening_hours'   => $this->getOpeningHours(),
			'gps'             => $this->getGpsCoordinates(),
			'services'        => $this->getServices(),
			'payment_methods' => $this->getPaymentMethods(),
			'address'         => $this->getAddress(),
			'remarks'         => $this->getRemarks(),
			'link'            => $this->getLink()
		];
	}
}
