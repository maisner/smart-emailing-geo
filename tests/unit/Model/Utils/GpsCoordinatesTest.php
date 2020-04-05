<?php namespace Maisner\Tests\Model\Utils;

use Maisner\App\Model\Utils\GpsCoordinates;
use Maisner\App\Tests\Model\SalesPoint\AbstractSalesPointTest;

class GpsCoordinatesTest extends AbstractSalesPointTest {


	public function testConstructor(): void {
		$this->assertInstanceOf(GpsCoordinates::class, new GpsCoordinates(10.25, 43.196));
	}

	public function testGetters(): void {
		$gps = new GpsCoordinates(10.25, 43.196);

		$this->assertSame(10.25, $gps->getLatitude());
		$this->assertSame(43.196, $gps->getLongitude());
	}
}
