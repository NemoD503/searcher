<?php

namespace NemoD503\Searcher;

interface ComparatorInterface
{
	public function addline($line='');
	
	public function getDuplicates($numbeOfDuplicates = null);
}