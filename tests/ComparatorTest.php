<?php

use NemoD503\Searcher\Comparator;

class ComparatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_shoul_not_return_empty_array_when_we_dont_set_any_lines()
    {
  
        $this->assertEmpty($this->fireComparator());
    }


    /**
     * @test
     */
    public function it_shoul_return_array_with_line_number_and_amount_of_duplicates_for_one_line()
    {
        $lines = [
            'some text line'
        ];
  
        $this->assertArraySubset([0 => 1],$this->fireComparator($lines));
    }


    /**
     * @test
     */
    public function it_shoul_return_empty_array_when_we_get_zero_number_of_duplicates()
    {
        $lines = [
            'some text line'
        ];

        $this->assertEmpty($this->fireComparator($lines, 0));
    }


    /**
     * @test
     */
    public function it_shoul_return_0_2_for_first_line_duplication()
    {
        $lines = [
            'some text line',
            'some text line'
        ];
        
        $this->assertArraySubset([0 => 2],$this->fireComparator($lines));
    }


    /**
     * @test
     */
    public function it_shoul_return_1_3_for_first_line_duplication()
    {
        $lines = [
            'some line',
            'some text line',
            'some text line',
            'some text line',
        ];
        
        $result = $this->fireComparator($lines, 1);

        $this->assertArraySubset([1 => 3], $result);
        $this->assertCount(1, $result);
    }


    /**
     * @test
     */
    public function test_with_empty_values()
    {
        $lines = [
            'a',
            '',
            'b',
            '',
            'c',
            '',
            'c',
        ];
        $result = $this->fireComparator($lines,1 );
        // var_dump($result);
        $this->assertArraySubset([4 => 2],$result);
        $this->assertCount(1, $result);
    }

    /**
     * @test
     */
    public function test_from_ipon_web_quiz()
    {
        $lines = [
            'a',
            'c',
            'b',
            'b',
            'c',
            'c',
            'c',
        ];

        $result = $this->fireComparator($lines,2 );
        $this->assertArraySubset([1 => 4, 2 => 2],$result);
        $this->assertCount(2, $result);
    }


    protected function fireComparator($lines = [], $numbeOfDuplicates = null)
    {
        $comparator = new Comparator();

        foreach ($lines as $lineNumber => $line) {
            $comparator->addLine($line);
        }

        return $comparator->getDuplicates($numbeOfDuplicates);
    }

}