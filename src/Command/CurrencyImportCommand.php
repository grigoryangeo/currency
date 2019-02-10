<?php

namespace App\Command;

use App\CurrencyLoader\CbrProvider;
use App\CurrencyLoader\EcbProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\CurrencyLoader\ProviderInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CurrencyImportCommand extends Command
{
    /** @var  []ProviderInterface */
    protected $currencyProviders = [];

    public function __construct(iterable $currencyProviders)
    {
        parent::__construct();
        foreach($currencyProviders as $currencyProvider) {
            if($currencyProvider instanceof ProviderInterface) {
                $this->currencyProviders[] = $currencyProvider;
            }
        }

        if(!count($this->currencyProviders)) {
            throw new \Exception('Not one currency provider found');
        }
    }

    protected function configure()
    {
        $this
            ->setName('app:currency:import')
            ->setHelp('This command import currency')
        ;;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->progressStart(count($this->currencyProviders));
        foreach($this->currencyProviders as $currencyProvider)
        {
            $code = $currencyProvider->getCurrencySource();
            $io->newLine();
            $io->title("Start import currency from $code");
            try {
                $currencyProvider->import($output);
            } catch (\Exception $e) {
                $output->writeln('<error>' . $e->getMessage() . '</error>');
            }
            $io->progressAdvance();
        }
        $io->progressFinish();
    }
}
