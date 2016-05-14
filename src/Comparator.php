<?php

namespace NemoD503\Searcher;

class Comparator implements ComparatorInterface
{
	
	protected $hashTable = [];

	protected $currentLineIndex = -1;


	/**
	 * Add new string for comparison with previous strings
	 * @param  string $line line 
	 * @return void
	 */
	public function addline($line='')
	{
		$this->currentLineIndex++;

		if (empty($line)) {
			return;
		}

		$lineHash = $this->makeHash($line);

		if (empty($this->hashTable[$lineHash])) {
			$this->hashTable[$lineHash] = [
				'count' => 1,
				'lineNumber' => $this->currentLineIndex
			];
			return;
		}
		
		$this->hashTable[$lineHash]['count']++;
	}


	/**
	 * Method returns an array with indexes of the lines and number of duplicates
	 * @param  int $numbeOfDuplicates You can limit number of returned items in array.
	 * @return array     array of results (index of the line => count of duplicates ) [0=>1]
	 */
	public function getDuplicates($numbeOfDuplicates = null)
	{
		$result = [];

		$this->sortDescHashtable();

		$sliced = array_slice($this->hashTable, 0, $numbeOfDuplicates);

		foreach ($sliced as $item) {
			$result[$item['lineNumber']] = $item['count'];
		}

		return $result;
	}


	/**
	 * Method for making hash from string
	 * @param  string $line
	 * @return string       hash from string
	 */
	protected function makeHash($line)
	{
		//md4 is not secure, but the most fast (http://php.net/manual/ru/function.hash.php#89574)
		return hash('md4', $line); 
	}


	/**
	 * Method for sorting hashTable Desc by number of duplicates
	 * @return [type] [description]
	 */
	protected function sortDescHashtable()
	{
		uasort($this->hashTable, function($a, $b){
			if ($a == $b) {
			    return 0;
		    }
			return ($a['count'] > $b['count'])? -1 : 1;
		});
	}
}