<?php declare(strict_types = 1);

namespace Maisner\App\Console;


use Maisner\App\Model\SalesPoint\SalesPointRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearSalesPointsCommand extends Command {

	private SalesPointRepository $salesPointRepository;

	public function __construct(SalesPointRepository $salesPointRepository) {
		parent::__construct('salesPoints:clear');
		$this->salesPointRepository = $salesPointRepository;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int {
		if ($this->salesPointRepository->deleteAll()) {
			$output->writeln('Success');
		} else {
			$output->writeln('Error!');

			return 1;
		}

		return 0;
	}

}
