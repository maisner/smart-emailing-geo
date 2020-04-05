<?php

namespace Maisner\Tests\Model\Utils\IdentityMap;

use Codeception\Test\Unit;
use Maisner\App\Model\EntityInterface;
use Maisner\App\Model\Utils\IdentityMap\IdentityMap;
use Maisner\Tests\UnitTester;
use Nette\Utils\ArrayList;

class IdentityMapTest extends Unit {

	protected UnitTester $tester;

	protected IdentityMap $identityMap;

	protected function _before() {
		$this->identityMap = new IdentityMap();
	}

	public function testAddEntity(): void {
		$this->identityMap->add($this->createEntityMock(1));

		$this->assertSame(1, $this->identityMap->getAll()->count());
		$this->assertInstanceOf(EntityInterface::class, $this->identityMap->get(1));
		$this->assertSame(1, $this->identityMap->get(1)->getId());
	}

	public function testAddMultipleEntities(): void {
		for ($i = 1; $i <= 10000; $i++) {
			$this->identityMap->add($this->createEntityMock($i));
		}

		$this->assertSame(10000, $this->identityMap->getAll()->count());
		$this->assertSame(6789, $this->identityMap->get(6789)->getId());
	}

	public function testGetAll(): void {
		$this->assertInstanceOf(ArrayList::class, $this->identityMap->getAll());
	}

	public function testRemoveIdentity(): void {
		for ($i = 1; $i <= 10; $i++) {
			$this->identityMap->add($this->createEntityMock($i));
		}

		$this->assertSame(6, $this->identityMap->get(6)->getId());

		$this->identityMap->remove(6);
		$this->assertNull($this->identityMap->get(6));
	}

	protected function createEntityMock(int $id) {
		$entityMock = $this->createMock(EntityInterface::class);
		$entityMock->method('getId')->willReturn($id);

		return $entityMock;
	}
}
