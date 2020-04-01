<?php declare(strict_types = 1);

namespace Maisner\App\Model\Utils;


use Maisner\App\Model\Exception\InvalidArgumentException;

class IP {

	private string $ip;

	public function __construct(string $ip) {
		if (\filter_var($ip, \FILTER_VALIDATE_IP) === FALSE) {
			throw new InvalidArgumentException(\sprintf('IP %s is invalid', $ip));
		}
		$this->ip = $ip;
	}

	public function getValue(): string {
		return $this->ip;
	}

	public function __toString(): string {
		return $this->getValue();
	}
}
