<?php declare(strict_types = 1);

namespace Maisner\App\Model\SalesPoint;


use JsonSerializable;
use Nette\SmartObject;
use Nette\Utils\ArrayList;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class OpeningHoursCollection implements JsonSerializable {

	use SmartObject;

	protected ArrayList $list;

	public function __construct() {
		$this->list = new ArrayList();
	}

	public function add(OpeningHours $openingHours): OpeningHoursCollection {
		$this->list[] = $openingHours;

		return $this;
	}

	public function count(): int {
		return $this->list->count();
	}

	/**
	 * @return ArrayList|OpeningHours[]
	 */
	public function getAll(): ArrayList {
		return clone $this->list;
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize(): array {
		$result = [];

		foreach ($this->list as $item) {
			$result[] = $item;
		}

		return $result;
	}

	/**
	 * @param string $json
	 * @return OpeningHoursCollection
	 * @throws JsonException
	 */
	public static function fromJson(string $json): OpeningHoursCollection {
		$collection = new OpeningHoursCollection();

		foreach (Json::decode($json, Json::FORCE_ARRAY) as $item) {
			$collection->add(new OpeningHours((int)$item['from'], (int)$item['to'], $item['hours']));
		}

		return $collection;
	}
}
