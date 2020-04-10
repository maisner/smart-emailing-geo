<?php declare(strict_types = 1);

namespace Maisner\App\Model\Utils;


use Exception;
use Nette\SmartObject;
use Nette\Utils\DateTime;

class DateTimeProvider {

	use SmartObject;

	protected DateTime $currentDateTime;

	protected string $timeZone = 'Europe/Prague';

	/**
	 * DateTimeProvider constructor.
	 * @param string|null $currentDateTimeString
	 * @throws Exception
	 */
	public function __construct(?string $currentDateTimeString = NULL) {
		$this->currentDateTime = new DateTime($currentDateTimeString ?? 'now', new \DateTimeZone($this->timeZone));
	}

	public function getCurrent(): DateTime {
		return clone $this->currentDateTime;
	}

	/**
	 * @param string $timestamp
	 * @return DateTime
	 * @throws Exception
	 */
	public function getByTimestamp(string $timestamp): DateTime {
		$datetime = new DateTime('@' . $timestamp);
		$datetime->setTimezone(new \DateTimeZone($this->timeZone));

		return $datetime;
	}

}
