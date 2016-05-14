<?php

namespace NemoD503\Searcher;

use SplFileObject;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class FindDuplicatesCommand extends Command
{
	private $needToStop = false;
	private $comparator;

	/**
	 * sets comparator for this command, it`s better to use somthig like DI in a real project
	 * @param ComparatorInterface $comparator [description]
	 */
	public function setComparator(ComparatorInterface $comparator)
	{
		$this->comparator = $comparator;
	}

	protected function configure()
	{
		$this->setName('find:duplicates')
		->setDescription('Find duplicate lines in the file')
		->addArgument(
			'file',
			InputArgument::OPTIONAL,
			'In what file you whant find duplicate lines?'
			)
		->addArgument(
			'numberOfDuplicates',
			InputArgument::OPTIONAL,
			'How many duplicates you want to get?',
			null
			);
	}

	
	protected function interact(InputInterface $input, OutputInterface $output)
	{
		//Here You can ask filename in the future
		if (!$input->getArgument('file')) {
			$this->needToStop = true;
			$this->writeError('You need to set file name in the first argument', $output);
		}

		if (!$this->comparator) {
			throw new RuntimeException("comparator is not set for this class", 1);
			
		}
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		if ($this->needToStop) {
			return;
		}

		try {

			$file = new SplFileObject($input->getArgument('file')); 

			foreach ($file as $lineNumber => $line) { 
				//line by line, because we can work with very big file, bigger, than RAM
				$this->comparator->addLine(trim($line));
			}

			foreach ($this->comparator->getDuplicates($input->getArgument('numberOfDuplicates')) as $lineNumber => $amountOfDuplicates) {
				$file->seek($lineNumber);
				$output->writeln(sprintf('%s: %d', trim($file->current()), $amountOfDuplicates));
			}


		} catch (RuntimeException $e) {

			$this->writeError('The file can not be opened: '.$e->getMessage(), $output);
			return;
		}
	}


	protected function writeError($error, $output)
	{
		$output->writeln(sprintf('<error>%s</>', $error));
	}
}