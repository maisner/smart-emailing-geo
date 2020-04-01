<?php declare(strict_types = 1);

namespace Maisner\App\Model\SalesPoint;


use Maisner\App\Model\Exception\InvalidArgumentException;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Nette\Utils\ArrayList;
use Nette\Utils\DateTime;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class SalesPointFacade {

	use SmartObject;

	private SalesPointRepository $salesPointRepository;

	public function __construct(SalesPointRepository $salesPointRepository) {
		$this->salesPointRepository = $salesPointRepository;
	}

	/**
	 * @param bool     $onlyOpen
	 * @param SortBy   $sortBy
	 * @param DateTime $dateTime
	 * @return ArrayList|SalesPoint[]
	 * @throws InvalidArgumentException
	 * @throws JsonException
	 */
	public function find(bool $onlyOpen, SortBy $sortBy, DateTime $dateTime): ArrayList {
		$by = [];
		if ($onlyOpen) {
			$by['id'] = $this->filterOnlyOpened($dateTime);
		}

		return $this->salesPointRepository->findBy($by, $sortBy);
	}

	/**
	 * @param DateTime $dateTime
	 * @return array|int[]
	 * @throws InvalidArgumentException
	 * @throws JsonException
	 */
	protected function filterOnlyOpened(DateTime $dateTime): array {
		$openedIds = [];

		$day = (int)$dateTime->format('w');
		$hours = (int)$dateTime->format('G');
		$minutes = (int)$dateTime->format('i');
		$seconds = (int)$dateTime->format('s');

		$timestamp = $this->strtotime(\sprintf('%s:%s:%s', $hours, $minutes, $seconds));

		/** @var ActiveRow $row */
		foreach ($this->salesPointRepository->getTable()->select('id')->select('opening_hours') as $row) {
			foreach (Json::decode($row->offsetGet('opening_hours'), Json::FORCE_ARRAY) as $openingHours) {

				if ($day < $openingHours['from'] || $day > $openingHours['to']) {
					continue;
				}

				foreach (\explode(',', $openingHours['hours']) as $period) {
					$exploded = \explode('-', $period, 2);

					if (\count($exploded) !== 2) {
						continue;
					}

					$timeFrom = $exploded[0];
					$timeTo = $exploded[1];

					$timeFrom = $timeFrom !== '24:00' ? $timeFrom . ':00' : '23:59:59';
					$timeTo = $timeTo !== '24:00' ? $timeTo . ':00' : '23:59:59';

					if ($timestamp < $this->strtotime($timeFrom) || $timestamp > $this->strtotime($timeTo)) {
						continue;
					}

					$openedIds[] = (int)$row->offsetGet('id');

					break 2;
				}
			}
		}

		return \array_unique($openedIds);
	}

	/**
	 * @param string $string
	 * @return int
	 * @throws InvalidArgumentException
	 */
	protected function strtotime(string $string): int {
		$time = \strtotime($string);

		if (!\is_int($time)) {
			throw new InvalidArgumentException(\sprintf('Convert string "%s" to timestamp failed.', $string));
		}

		return $time;
	}


}
