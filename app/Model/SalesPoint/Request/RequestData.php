<?php declare(strict_types = 1);

namespace Maisner\App\Model\SalesPoint\Request;


use Maisner\App\Model\SalesPoint\SortBy;
use Maisner\App\Model\Utils\IP;
use Nette\Utils\DateTime;

class RequestData {

	private SortBy $sortBy;

	private bool $onlyOpen;

	private DateTime $dateTime;

	private ?IP $ip;

	public function __construct(SortBy $sortBy, bool $onlyOpen, DateTime $dateTime, ?IP $ip = NULL) {
		$this->sortBy = $sortBy;
		$this->onlyOpen = $onlyOpen;
		$this->dateTime = $dateTime;
		$this->ip = $ip;
	}

	public function getSortBy(): SortBy {
		return $this->sortBy;
	}

	public function isOnlyOpen(): bool {
		return $this->onlyOpen;
	}

	public function getDateTime(): DateTime {
		return $this->dateTime;
	}

	public function getIp(): ?IP {
		return $this->ip;
	}
}
