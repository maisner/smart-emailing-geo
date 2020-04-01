<?php namespace Maisner\Tests\Model\SalesPoint;

use Maisner\App\Model\SalesPoint\OpeningHoursCollection;
use Maisner\App\Model\SalesPoint\SalesPoint;
use Maisner\App\Model\SalesPoint\Type;
use Maisner\App\Model\Utils\GpsCoordinates;
use Maisner\App\Tests\Model\SalesPoint\AbstractSalesPointTest;

class SalesPointTest extends AbstractSalesPointTest {

	private SalesPoint $salesPoint;

	protected function _before() {
		$this->salesPoint = new SalesPoint(
			3,
			'dp1',
			$this->createTypeMock(Type::TICKET_MACHINE),
			'Sales point name',
			$this->createOpeningHoursCollectionMock(),
			$this->createGpsCoordinatesMock(40.8, 20.8),
			6,
			2,
			'Street 95, City 12345',
			'some remarks',
			'https://points.com'
		);
	}

	protected function _after() {
	}

	public function testGetters(): void {
		$this->assertSame(3, $this->salesPoint->getId());
		$this->assertSame('dp1', $this->salesPoint->getExternalId());
		$this->assertSame(Type::TICKET_MACHINE, (string)$this->salesPoint->getType());
		$this->assertSame('Sales point name', $this->salesPoint->getName());
		$this->assertInstanceOf(OpeningHoursCollection::class, $this->salesPoint->getOpeningHours());
		$this->assertInstanceOf(GpsCoordinates::class, $this->salesPoint->getGpsCoordinates());
		$this->assertSame(6, $this->salesPoint->getServices());
		$this->assertSame(2, $this->salesPoint->getPaymentMethods());
		$this->assertSame('Street 95, City 12345', $this->salesPoint->getAddress());
		$this->assertSame('some remarks', $this->salesPoint->getRemarks());
		$this->assertSame('https://points.com', $this->salesPoint->getLink());
	}


}
