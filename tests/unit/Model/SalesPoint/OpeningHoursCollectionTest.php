<?php namespace Maisner\Tests\Model\SalesPoint;

use Maisner\App\Model\SalesPoint\OpeningHours;
use Maisner\App\Model\SalesPoint\OpeningHoursCollection;
use Maisner\App\Tests\Model\SalesPoint\AbstractSalesPointTest;

class OpeningHoursCollectionTest extends AbstractSalesPointTest {

	protected OpeningHoursCollection $collection;

	public function _before() {
		parent::_before();

		$this->collection = new OpeningHoursCollection();
	}


	public function testAdd(): void {
		$this->assertSame(0, $this->collection->count());

		$this->collection->add($this->createOpeningHours(2, 4, '09:00-12:00'));
		$this->assertSame(1, $this->collection->count());
	}

	public function testGetAll(): void {
		$this->collection->add($this->createOpeningHours(2, 4, '09:00-12:00'));
		$this->collection->add($this->createOpeningHours(4, 6, '09:00-10:00'));

		$this->assertSame(2, $this->collection->getAll()->count());

		foreach ($this->collection->getAll() as $item) {
			$this->assertInstanceOf(OpeningHours::class, $item);
		}
	}

	public function testJsonSerialize(): void {
		$this->collection->add($this->createOpeningHours(2, 4, '09:00-12:00'));
		$this->collection->add($this->createOpeningHours(4, 6, '09:00-10:00'));

		$expected = '[{"from":2,"to":4,"hours":"09:00-12:00"},{"from":4,"to":6,"hours":"09:00-10:00"}]';
		$this->assertSame($expected, \json_encode($this->collection, JSON_THROW_ON_ERROR, 512));
	}

	public function testCreateFromJson(): void {
		$json = '[{"from":0,"to":4,"hours":"09:00-17:00"},{"from":5,"to":6,"hours":"09:00-10:00"}]';
		$collection = OpeningHoursCollection::fromJson($json);

		$openingHours = $collection->getAll();

		$this->assertSame(0, $openingHours->offsetGet(0)->getFrom());
		$this->assertSame(4, $openingHours->offsetGet(0)->getTo());
		$this->assertSame('09:00-17:00', $openingHours->offsetGet(0)->getHours());

		$this->assertSame(5, $openingHours->offsetGet(1)->getFrom());
		$this->assertSame(6, $openingHours->offsetGet(1)->getTo());
		$this->assertSame('09:00-10:00', $openingHours->offsetGet(1)->getHours());
	}

	protected function createOpeningHours(int $from, int $to, string $hours): OpeningHours {
		return new OpeningHours($from, $to, $hours);
	}
}
