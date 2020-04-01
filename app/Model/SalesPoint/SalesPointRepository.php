<?php declare(strict_types = 1);

namespace Maisner\App\Model\SalesPoint;


use Maisner\App\Model\Exception\EntityNotFoundException;
use Maisner\App\Model\Exception\EntityPersistException;
use Maisner\App\Model\Exception\InvalidArgumentException;
use Maisner\App\Model\Utils\GpsCoordinates;
use Maisner\App\Model\Utils\IdentityMap\Factory\IdentityMapFactoryInterface;
use Maisner\App\Model\Utils\IdentityMap\IdentityMapInterface;
use Nette\Database\Context;
use Nette\Database\DriverException;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\ArrayList;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Strings;
use Tracy\ILogger;

class SalesPointRepository {

	public const TABLE = 'sales_point';

	protected Context $database;

	protected IdentityMapInterface $identityMap;

	protected ILogger $logger;

	public function __construct(Context $database, IdentityMapFactoryInterface $identityMapFactory, ILogger $logger) {
		$this->database = $database;
		$this->logger = $logger;
		$this->identityMap = $identityMapFactory->create();
	}

	public function getDatabase(): Context {
		return $this->database;
	}

	public function getTable(): Selection {
		return $this->database->table(self::TABLE);
	}

	/**
	 * @param int $id
	 * @return SalesPoint
	 * @throws EntityNotFoundException
	 * @throws JsonException
	 */
	public function getById(int $id): SalesPoint {
		$result = $this->findBy(['id' => $id], new SortBy(SortBy::ID_ASC));

		if (!$result->offsetExists(0)) {
			throw new EntityNotFoundException(\sprintf('Entity SalesPoint with id %s not found', $id));
		}

		return $result->offsetGet(0);
	}

	/**
	 * @param array|mixed[] $by
	 * @param SortBy        $sortBy
	 * @return ArrayList|SalesPoint[]
	 * @throws JsonException
	 * @throws InvalidArgumentException
	 */
	public function findBy(array $by, SortBy $sortBy): ArrayList {
		$result = new ArrayList();

		$rows = $this->getTable()
			->where($by)
			->order($this->getOrderByString($sortBy))
			->fetchAll();

		/** @var ActiveRow $row */
		foreach ($rows as $row) {
			$result[] = $this->salesPointFactory($row);
		}

		return $result;
	}

	/**
	 * @param SalesPoint $salesPoint
	 * @return SalesPoint
	 * @throws EntityNotFoundException
	 * @throws EntityPersistException
	 * @throws JsonException
	 */
	public function save(SalesPoint $salesPoint): SalesPoint {
		if ($salesPoint->getId() !== 0) {
			return $salesPoint;
		}

		$data = [
			'type'            => (string)$salesPoint->getType(),
			'name'            => $salesPoint->getName(),
			'opening_hours'   => Json::encode($salesPoint->getOpeningHours()),
			'lon'             => $salesPoint->getGpsCoordinates()->getLongitude(),
			'lat'             => $salesPoint->getGpsCoordinates()->getLatitude(),
			'services'        => $salesPoint->getServices(),
			'payment_methods' => $salesPoint->getPaymentMethods(),
			'address'         => $salesPoint->getAddress(),
			'remarks'         => $salesPoint->getRemarks(),
			'link'            => $salesPoint->getLink(),
			'external_id'     => $salesPoint->getExternalId()
		];

		try {
			$row = $this->database->table(self::TABLE)->insert($data);

			if (!$row instanceof ActiveRow) {
				throw new EntityPersistException('Entity persist SalesPoint failed');
			}

			return $this->getById((int)$row->offsetGet('id'));
		} catch (UniqueConstraintViolationException $e) {
			throw new EntityPersistException(
				\sprintf('Sales point with external_id "%s" is exist', $salesPoint->getExternalId())
			);
		} catch (DriverException $e) {
			$this->logger->log($e, ILogger::ERROR);

			throw new EntityPersistException('Entity persist SalesPoint failed', 0, $e);
		}
	}

	public function deleteAll(): bool {
		try {
			$this->database->table(self::TABLE)->delete();
		} catch (DriverException $e) {
			$this->logger->log($e, ILogger::ERROR);

			return FALSE;
		}

		return TRUE;
	}

	/**
	 * @param SortBy $sortBy
	 * @return string
	 * @throws InvalidArgumentException
	 */
	protected function getOrderByString(SortBy $sortBy): string {
		if ($sortBy->isDistanceSort()) {
			$gps = $sortBy->getActualGps();
			if ($gps === NULL) {
				throw new InvalidArgumentException('Actual GPS is required for distance sort');
			}

			return \sprintf(
				'((lat - %s)*(lat - %s)) + ((lon - %s)*(lon - %s)) %s',
				$gps->getLatitude(),
				$gps->getLatitude(),
				$gps->getLongitude(),
				$gps->getLongitude(),
				Strings::upper(\str_replace('distance_', '', $sortBy->getValue()))
			);
		}

		return Strings::upper(\str_replace('_', ' ', $sortBy->getValue()));
	}

	/**
	 * @param ActiveRow $row
	 * @return SalesPoint
	 * @throws JsonException
	 */
	protected function salesPointFactory(ActiveRow $row): SalesPoint {
		$entity = $this->identityMap->get((int)$row->offsetGet('id'));

		if ($entity instanceof SalesPoint) {
			return $entity;
		}

		$openingHoursCollection = OpeningHoursCollection::fromJson($row->offsetGet('opening_hours'));

		$entity = new SalesPoint(
			(int)$row->offsetGet('id'),
			$row->offsetGet('external_id'),
			new Type($row->offsetGet('type')),
			$row->offsetGet('name'),
			$openingHoursCollection,
			new GpsCoordinates($row->offsetGet('lat'), $row->offsetGet('lon')),
			(int)$row->offsetGet('services'),
			(int)$row->offsetGet('payment_methods'),
			$row->offsetGet('address'),
			$row->offsetGet('remarks'),
			$row->offsetGet('link')
		);

		$this->identityMap->add($entity);

		return $entity;
	}

}
