<?php declare(strict_types = 1);

namespace Maisner\App\Presenters;

use Maisner\App\Model\Exception\HttpException;
use Maisner\App\Model\Exception\InvalidArgumentException;
use Maisner\App\Model\SalesPoint\Request\RequestDataFactory;
use Maisner\App\Model\SalesPoint\SalesPointFacade;
use Nette;

final class SalesPointPresenter extends Nette\Application\UI\Presenter {

	/** @var SalesPointFacade @inject */
	public SalesPointFacade $salesPointFacade;

	/** @var RequestDataFactory @inject */
	public RequestDataFactory $requestDataFactory;

	/**
	 * @throws Nette\Application\AbortException
	 * @throws HttpException
	 * @throws Nette\Utils\JsonException
	 * @throws InvalidArgumentException
	 */
	public function actionDefault(): void {
		$requestData = $this->requestDataFactory->create($this->getHttpRequest());

		$data = [];

		foreach (
			$this->salesPointFacade->find(
				$requestData->isOnlyOpen(),
				$requestData->getSortBy(),
				$requestData->getDateTime()
			) as $salesPoint
		) {
			$data[] = $salesPoint;
		}

		$this->sendJson(
			[
				'filter_params' => [
					'only_open' => $requestData->isOnlyOpen(),
					'datetime'  => (string)$requestData->getDateTime(),
					'sort_by'   => $requestData->getSortBy()->getValue(),
					'ip'        => $requestData->getIp() !== NULL ? (string)$requestData->getIp() : NULL,
				],
				'data'          => $data
			]
		);
	}
}
