<?php declare(strict_types = 1);

namespace Maisner\App;

use Nette\Configurator;


class Bootstrap {
	public static function boot(): Configurator {
		if (!isset($_ENV['APP_INSTANCE_NAME'])) {
			throw new \InvalidArgumentException('Must by set ENV variable "APP_INSTANCE_NAME"');
		}

		$logDir = \sprintf('%s/../log/%s', __DIR__, $_ENV['APP_INSTANCE_NAME']);
		$tempDir = \sprintf('%s/../temp/%s', __DIR__, $_ENV['APP_INSTANCE_NAME']);

		$configurator = new Configurator;

		$configurator->setDebugMode($_ENV['DEBUG'] === '1');
		$configurator->setTimeZone('Europe/Prague');

		$configurator->enableTracy($logDir);
		$configurator->setTempDirectory($tempDir);

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig(__DIR__ . '/config/config.neon');
		$configurator->addConfig(__DIR__ . '/config/config.php');
		$configurator->addConfig(__DIR__ . '/config/local.neon');

		return $configurator;
	}
}
