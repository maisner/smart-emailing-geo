<?php declare(strict_types = 1);

namespace Maisner\App\Model\Utils;


use Maisner\App\Model\Exception\InvalidArgumentException;
use Nette\Http\Request;
use Nette\InvalidStateException;

class IPClientFactory {
	private Request $request;

	public function __construct(Request $request) {
		$this->request = $request;
	}

	/**
	 * @return IP
	 * @throws InvalidArgumentException
	 */
	public function create(): IP {
		$ip = $_ENV['APP_CLIENT_IP'] ?? NULL;

		if ($ip === NULL) {
			$ip = $this->request->getRemoteAddress();
		}

		if ($ip === NULL) {
			throw new InvalidStateException('Client IP from request not exist');
		}

		return new IP($ip);
	}

}
