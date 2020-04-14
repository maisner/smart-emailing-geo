<?php

namespace Maisner\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Api extends \Codeception\Module {

	/**
	 * Defined ENV variables in docker-compose file
	 *
	 * @return string
	 */
	public function getCurrentDatetimeForTests(): string {
		return '2020-04-06 16:45:00';
	}

	/**
	 * Defined ENV variables in docker-compose file
	 *
	 * @return string
	 */
	public function getCurrentClientIpForTests(): string {
		return '131.117.214.28';
	}
}
