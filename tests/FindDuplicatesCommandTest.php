<?php

use NemoD503\Searcher\FindDuplicatesCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Exception\RuntimeException;
use NemoD503\Searcher\Comparator;

class FindDuplicatesCommandTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function it_should_show_error_when_first_argument_is_missing()
	{

		$this->assertEquals(
			'You need to set file name in the first argument'.PHP_EOL, 
			$this->executeCommand([]));

	}

	/**
	 * @test
	 */
	public function it_should_show_error_when_file_can_not_be_opened()
	{

		$this->assertRegExp(
			'/^The file can not be opened.*?/'.PHP_EOL, 
			$this->executeCommand(['file' => 	'non_existing_file.nef']));

	}

	/**
	 * Incapsulated command execution
	 * @param  array  $arguments for command
	 * @return string  Output the result of the command execution
	 */
	protected function executeCommand($arguments = [])
	{
		$application = new Application();
		$command = new FindDuplicatesCommand();
		$command->setComparator(new Comparator);
		$application->add($command);

		$command = $application->find('find:duplicates');
		$commandTester = new CommandTester($command);
		$commandTester->execute($arguments);
		return $commandTester->getDisplay();
	}
}