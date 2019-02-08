<?php

namespace App\Command;

use App\CurrencyLoader\CbrProvider;
use App\CurrencyLoader\EcbProvider;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CurrencyImportCommand extends ContainerAwareCommand
{
    /** @var  CbrProvider */
    protected $cbrProvider;

    /** @var  EcbProvider */
    protected $ecbProvider;

    public function __construct(CbrProvider $cbrProvider, EcbProvider $ecbProvider)
    {
        parent::__construct();
        $this->cbrProvider = $cbrProvider;
        $this->ecbProvider = $ecbProvider;
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
        $kernel    = $this->getApplication()->getKernel();
        $container = $kernel->getContainer();

        $output->writeln('<comment>Start import currency from CBR</comment>');
        try {
            $this->cbrProvider->import($output);
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }

        $output->writeln('<comment>Start import currency from ECB</comment>');
        try {
            $this->ecbProvider->import($output);
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}
