<?php

namespace Maisner\Tests\Model\Utils\IdentityMap\Factory;

use Codeception\Test\Unit;
use Maisner\App\Model\Utils\IdentityMap\Factory\IdentityMapFactory;
use Maisner\App\Model\Utils\IdentityMap\IdentityMap;
use Maisner\Tests\UnitTester;

class IdentityMapFactoryTest extends Unit {

	protected UnitTester $tester;

	protected IdentityMapFactory $identityMapFactory;


	protected function _before() {
		$this->identityMapFactory = new IdentityMapFactory();
	}

	public function testCreate(): void {
		$this->assertInstanceOf(IdentityMap::class, $this->identityMapFactory->create());
	}
}
