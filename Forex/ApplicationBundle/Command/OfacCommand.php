<?php

namespace Forex\ApplicationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Command Line to update the Ofac list
 * The Cron job will run everyday at 1am, as the list can be updated just once per day
 *
 * Class OfacCommand
 * @package Forex\ApplicationBundle\Command
 */
class OfacCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('update:ofac')
			->setDescription('Update Ofac list')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$response = $this->getContainer()->get('ofac.service')->updateOfac();

		$output->writeln($response);
	}
}
