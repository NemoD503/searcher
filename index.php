<?php

require "vendor/autoload.php";

use Symfony\Component\Console\Application;
use NemoD503\Searcher\FindDuplicatesCommand;
use NemoD503\Searcher\Comparator;

$application = new Application();
$command = new FindDuplicatesCommand();
$command->setComparator(new Comparator);
$application->add($command);
$application->run();