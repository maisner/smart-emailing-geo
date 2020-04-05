<?php


namespace Maisner\App\Tests\Model\SalesPoint;


use Codeception\Test\Unit;
use Maisner\App\Model\SalesPoint\OpeningHours;
use Maisner\App\Model\SalesPoint\OpeningHoursCollection;
use Maisner\App\Model\SalesPoint\Type;
use Maisner\App\Model\Utils\GpsCoordinates;
use Maisner\Tests\UnitTester;
use PHPUnit\Framework\MockObject\MockObject;

abstract class AbstractSalesPointTest extends Unit {

	protected UnitTester $tester;

	/**
	 * @param string $type
	 * @return Type|MockObject
	 */
	protected function createTypeMock(string $type) {
		$mock = $this->createMock(Type::class);
		$mock->method('getValue')->willReturn($type);
		$mock->method('__toString')->willReturn($type);

		return $mock;
	}

	/**
	 * @param int    $from
	 * @param int    $to
	 * @param string $hours
	 * @return OpeningHours|MockObject
	 */
	protected function createOpeningHoursMock(int $from, int $to, string $hours) {
		$mock = $this->createMock(OpeningHours::class);
		$mock->method('getFrom')->willReturn($from);
		$mock->method('getTo')->willReturn($to);
		$mock->method('getHours')->willReturn($hours);

		return $mock;
	}

	/**
	 * @return OpeningHoursCollection|MockObject
	 */
	protected function createOpeningHoursCollectionMock() {
		return $this->createMock(OpeningHoursCollection::class);
	}

	protected function createGpsCoordinatesMock(float $latitude, float $longitude) {
		$mock = $this->createMock(GpsCoordinates::class);
		$mock->method('getLatitude')->willReturn($latitude);
		$mock->method('getLongitude')->willReturn($longitude);

		return $mock;
	}

}
