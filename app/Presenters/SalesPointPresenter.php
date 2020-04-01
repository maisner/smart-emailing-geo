<?php declare(strict_types = 1);

namespace Maisner\App\Presenters;

use Maisner\App\Model\Exception\HttpException;
use Maisner\App\Model\Exception\InvalidArgumentException;
use Maisner\App\Model\Geolocation\Service\IpGeolocation;
use Maisner\App\Model\SalesPoint\SalesPointFacade;
use Maisner\App\Model\SalesPoint\SalesPointRepository;
use Maisner\App\Model\SalesPoint\SortBy;
use Maisner\App\Model\Utils\IP;
use Nette;
use Nette\Utils\DateTime;
use Throwable;

final class SalesPointPresenter extends Nette\Application\UI\Presenter {

	/** @var SalesPointRepository @inject */
	public SalesPointRepository $salesPointRepository;

	/** @var SalesPointFacade @inject */
	public SalesPointFacade $salesPointFacade;

	/** @var IpGeolocation @inject */
	public IpGeolocation $ipGeolocation;

	/** @var IP @inject */
	public IP $ip;

	/**
	 * @throws Nette\Application\AbortException
	 * @throws HttpException
	 * @throws Nette\Utils\JsonException
	 * @throws InvalidArgumentException
	 */
	public function actionDefault(): void {
		$sortBy = $this->getSortFromRequest();
		$ip = $this->getIpFromRequest();
		$onlyOpen = $this->getOnlyOpenRequest();
		$datetime = $this->getDatetimeFromRequest();

		if ($ip instanceof IP && $sortBy->isDistanceSort()) {
			$sortBy->setActualGps($this->ipGeolocation->getGpsByIp($ip));
		}

		$data = [];

		foreach ($this->salesPointFacade->find($onlyOpen, $sortBy, $datetime) as $salesPoint) {
			$data[] = $salesPoint;
		}

		$this->sendJson(
			[
				'filter_params' => [
					'only_open' => $onlyOpen,
					'datetime'  => (string)$datetime,
					'sort_by'   => $sortBy->getValue(),
					'ip'        => $ip !== NULL ? (string)$ip : NULL,
				],
				'data'          => $data
			]
		);
	}

	protected function getSortFromRequest(): SortBy {
		$sort = $this->getHttpRequest()->getQuery('sort_by');

		if (Nette\Utils\Validators::isNone($sort)) {
			return new SortBy(SortBy::ID_ASC);
		}

		try {
			return new SortBy($sort);
		} catch (Throwable $e) {
			return new SortBy(SortBy::ID_ASC);
		}
	}

	protected function getIpFromRequest(): ?IP {
		$ip = $this->getHttpRequest()->getQuery('ip');

		if (Nette\Utils\Validators::isNone($ip)) {
			return NULL;
		}

		if ($ip === 'current') {
			return $this->ip;
		}

		try {
			return new IP($ip);
		} catch (InvalidArgumentException $e) {
			return NULL;
		}
	}

	protected function getOnlyOpenRequest(): bool {
		$onlyOpen = $this->getHttpRequest()->getQuery('only_open');

		return $onlyOpen === '1';
	}

	protected function getDatetimeFromRequest(): DateTime {
		$timestamp = $this->getHttpRequest()->getQuery('timestamp');

		if (Nette\Utils\Validators::isNone($timestamp)) {
			return new DateTime();
		}

		try {
			$datetime = new DateTime('@' . $timestamp);
			$datetime->setTimezone(new \DateTimeZone('Europe/Prague'));

			return $datetime;
		} catch (\Throwable $e) {
			return new DateTime();
		}
	}

}
