<?php declare(strict_types = 1);

namespace Maisner\App\Model\SalesPoint\Request;


use Maisner\App\Model\Exception\HttpException;
use Maisner\App\Model\Exception\InvalidArgumentException;
use Maisner\App\Model\Geolocation\Service\IpGeolocation;
use Maisner\App\Model\SalesPoint\SortBy;
use Maisner\App\Model\Utils\DateTimeProvider;
use Maisner\App\Model\Utils\IP;
use Nette\Http\IRequest;
use Nette\SmartObject;
use Nette\Utils\DateTime;
use Nette\Utils\Validators;
use Throwable;

class RequestDataFactory {

	use SmartObject;

	private IP $clientIp;

	private DateTimeProvider $dateTimeProvider;

	private IpGeolocation $ipGeolocation;

	public function __construct(IP $clientIp, DateTimeProvider $dateTimeProvider, IpGeolocation $ipGeolocation) {
		$this->clientIp = $clientIp;
		$this->dateTimeProvider = $dateTimeProvider;
		$this->ipGeolocation = $ipGeolocation;
	}


	/**
	 * @param IRequest $request
	 * @return RequestData
	 * @throws InvalidArgumentException
	 * @throws HttpException
	 */
	public function create(IRequest $request): RequestData {
		$sortBy = $this->getSortFromRequest($request);
		$ip = $this->getIpFromRequest($request);
		$onlyOpen = $this->getOnlyOpenRequest($request);
		$datetime = $this->getDatetimeFromRequest($request);

		if ($sortBy->isDistanceSort()) {
			if (!$ip instanceof IP) {
				throw new InvalidArgumentException('IP must be set for distance sorting');
			}

			$sortBy->setGps($this->ipGeolocation->getGpsByIp($ip));
		}

		return new RequestData($sortBy, $onlyOpen, $datetime, $ip);
	}

	protected function getSortFromRequest(IRequest $request): SortBy {
		$sort = $request->getQuery('sort_by');

		if (Validators::isNone($sort)) {
			return new SortBy(SortBy::ID_ASC);
		}

		try {
			return new SortBy($sort);
		} catch (Throwable $e) {
			return new SortBy(SortBy::ID_ASC);
		}
	}

	protected function getIpFromRequest(IRequest $request): ?IP {
		$ip = $request->getQuery('ip');

		if (Validators::isNone($ip)) {
			return NULL;
		}

		if ($ip === 'current') {
			return $this->clientIp;
		}

		try {
			return new IP($ip);
		} catch (InvalidArgumentException $e) {
			return NULL;
		}
	}

	protected function getOnlyOpenRequest(IRequest $request): bool {
		$onlyOpen = $request->getQuery('only_open');

		return $onlyOpen === '1';
	}

	protected function getDatetimeFromRequest(IRequest $request): DateTime {
		$timestamp = $request->getQuery('timestamp');

		if (Validators::isNone($timestamp)) {
			return $this->dateTimeProvider->getCurrent();
		}

		try {
			return $this->dateTimeProvider->getByTimestamp($timestamp);
		} catch (Throwable $e) {
			return $this->dateTimeProvider->getCurrent();
		}
	}
}
