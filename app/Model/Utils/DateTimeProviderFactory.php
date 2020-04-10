<?php declare(strict_types = 1);

namespace Maisner\App\Model\Utils;


use Exception;
use Nette\SmartObject;

class DateTimeProviderFactory {

	use SmartObject;

	/**
	 * @return DateTimeProvider
	 * @throws Exception
	 */
	public function create(): DateTimeProvider {
		return new DateTimeProvider($_ENV['APP_DATETIME_CURRENT'] ?? NULL);
	}

}
