<?php

namespace App\Service\Command;

use App\Exception\CreateMedicamenteException;
use App\Factory\MedicamenteFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateMedicamenteCommand extends Command
{
    protected static $defaultName = 'api:create-medicamente-and-save-to-db';

    public function __construct(
        private readonly MedicamenteFactory $medicamenteFactory,
        private readonly LoggerInterface $loggerMyApi,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Create new medicamente and save to database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Creating');
        $this->createMedicamenteExternalAPI();
        $io->info('Done!');

        return 0;
    }

    private function createMedicamenteExternalAPI()
    {
        try {
            $medicamente = $this->medicamenteFactory->fetchMedicamente();
            $this->medicamenteFactory->createMedicamentes($medicamente);
        } catch (CreateMedicamenteException $exception) {
            $this->loggerMyApi->error($exception);
        }
    }
}
