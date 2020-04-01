<?php namespace Maisner\Tests\Model\Utils;

use Maisner\App\Model\Exception\InvalidArgumentException;
use Maisner\App\Model\Utils\IP;
use Maisner\App\Tests\Model\SalesPoint\AbstractSalesPointTest;

class IPTest extends AbstractSalesPointTest {

	public function testConstructorIpV4(): void {
		$this->assertInstanceOf(IP::class, new IP('108.144.43.109'));
	}

	public function testConstructorInvalidIp(): void {
		$this->expectException(InvalidArgumentException::class);

		new IP('invalid.value');
	}

	public function testConstructorIpV6(): void {
		$this->assertInstanceOf(IP::class, new IP('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
	}

	public function testGeValue(): void {
		$ip = new IP('108.144.43.109');
		$this->assertSame('108.144.43.109', $ip->getValue());
	}
}
