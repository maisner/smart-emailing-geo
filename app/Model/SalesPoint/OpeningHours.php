<?php declare(strict_types = 1);

namespace Maisner\App\Model\SalesPoint;


use InvalidArgumentException;
use Nette\SmartObject;

class OpeningHours implements \JsonSerializable {

	use SmartObject;

	/**
	 * 0 - monday
	 * 6 - sunday
	 */
	private int $from;

	/**
	 * 0 - monday
	 * 6 - sunday
	 */
	private int $to;

	/**
	 * example - "9:00-17:00"
	 */
	private string $hours;

	public function __construct(int $from, int $to, string $hours) {
		$this->validateDayConstant($from);
		$this->validateDayConstant($to);

		$this->from = $from;
		$this->to = $to;
		$this->hours = $hours;
	}

	public function getFrom(): int {
		return $this->from;
	}

	public function getTo(): int {
		return $this->to;
	}

	public function getHours(): string {
		return $this->hours;
	}

	private function validateDayConstant(int $dayConstant): void {
		if ($dayConstant >= 0 && $dayConstant <= 6) {
			return;
		}

		throw new InvalidArgumentException(
			\sprintf('Invalid day constant value. Must be in range 0-6. Given %s', $dayConstant),
		);
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize(): array {
		return [
			'from'  => $this->from,
			'to'    => $this->to,
			'hours' => $this->hours
		];
	}
}
