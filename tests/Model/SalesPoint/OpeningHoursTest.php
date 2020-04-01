<?php namespace Maisner\Tests\Model\SalesPoint;

use Maisner\App\Model\SalesPoint\OpeningHours;
use Maisner\App\Tests\Model\SalesPoint\AbstractSalesPointTest;

class OpeningHoursTest extends AbstractSalesPointTest {


	public function testConstructor(): void {
		$this->assertInstanceOf(OpeningHours::class, new OpeningHours(0, 4, '07:00-12:00 13:00-17:00'));
	}

	public function testConstructorInvalidFromValue(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid day constant value. Must be in range 0-6. Given 10');

		new OpeningHours(10, 4, '07:00-12:00 13:00-17:00');
	}

	public function testConstructorInvalidToValue(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid day constant value. Must be in range 0-6. Given 7');

		new OpeningHours(0, 7, '07:00-12:00 13:00-17:00');
	}

	public function testConstructorInvalidBothValue(): void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid day constant value. Must be in range 0-6. Given 20');

		new OpeningHours(20, 10, '07:00-12:00 13:00-17:00');
	}

	public function testGetters(): void {
		$openingHours = new OpeningHours(0, 4, '07:00-12:00 13:00-17:00');

		$this->assertSame(0, $openingHours->getFrom());
		$this->assertSame(4, $openingHours->getTo());
		$this->assertSame('07:00-12:00 13:00-17:00', $openingHours->getHours());
	}

	public function testJsonSerialize(): void {
		$openingHours = new OpeningHours(0, 4, '07:00-12:00 13:00-17:00');

		$expected = '{"from":0,"to":4,"hours":"07:00-12:00 13:00-17:00"}';
		$this->assertSame($expected, \json_encode($openingHours, JSON_THROW_ON_ERROR, 512));
	}
}
