<?php declare(strict_types = 1);

namespace Maisner\App\Console;


use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Maisner\App\Model\SalesPoint\OpeningHours;
use Maisner\App\Model\SalesPoint\OpeningHoursCollection;
use Maisner\App\Model\SalesPoint\SalesPoint;
use Maisner\App\Model\SalesPoint\SalesPointRepository;
use Maisner\App\Model\SalesPoint\Type;
use Maisner\App\Model\Utils\GpsCoordinates;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tracy\ILogger;

class ImportSalesPointsCommand extends Command {

	private SalesPointRepository $salesPointRepository;

	private ClientInterface $httpClient;

	private ILogger $logger;

	public function __construct(
		SalesPointRepository $salesPointRepository,
		ClientInterface $httpClient,
		ILogger $logger
	) {
		parent::__construct('salesPoints:import');
		$this->salesPointRepository = $salesPointRepository;
		$this->httpClient = $httpClient;
		$this->logger = $logger;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int {
		try {
			$response = $this->httpClient->request('GET', 'http://data.pid.cz/pointsOfSale/xml/PointsOfSale.xml');
		} catch (GuzzleException $e) {
			$this->logger->log($e, 'CONSOLE');
			$output->writeln('Error HTTP client');

			return 1;
		}

		if ($response->getStatusCode() !== 200) {
			$output->writeln('Error HTTP code');

			return 1;
		}


		$xml = \simplexml_load_string((string)$response->getBody());

		if ($xml === FALSE) {
			$output->writeln('Error load xml');

			return 1;
		}

		foreach ($xml->point as $point) {
			$attrs = (array)$point->attributes();
			$attrs = $attrs['@attributes'];

			$externalId = $attrs['id'];
			$type = $attrs['type'];
			$name = $attrs['name'];
			$lat = $attrs['lat'];
			$lon = $attrs['lon'];
			$services = $attrs['services'];
			$paymentMethods = $attrs['payMethods'];
			$address = $attrs['address'] ?? NULL;
			$remarks = $attrs['remarks'] ?? NULL;
			$link = $attrs['link'] ?? NULL;

			$openingHoursCollection = new OpeningHoursCollection();

			foreach ($point->openingHours as $openingHours) {
				$attrs = (array)$openingHours->attributes();
				$attrs = $attrs['@attributes'];

				$from = $attrs['from'];
				$to = $attrs['to'];
				$hours = $attrs['hours'];

				$openingHours = new OpeningHours((int)$from, (int)$to, $hours);
				$openingHoursCollection->add($openingHours);
			}

			$salesPoint = new SalesPoint(
				0,
				$externalId,
				new Type($type),
				$name,
				$openingHoursCollection,
				new GpsCoordinates((float)$lat, (float)$lon),
				(int)$services,
				(int)$paymentMethods,
				$address,
				$remarks,
				$link
			);
			$this->salesPointRepository->save($salesPoint);
		}

		$output->writeln('Import success');

		return 0;
	}

}
