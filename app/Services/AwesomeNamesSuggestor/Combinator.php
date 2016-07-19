<?php

namespace App\Services\AwesomeNamesSuggestor;

class Combinator
{
    public function __construct($listsOfValues)
    {
        $this->listsOfValues = $listsOfValues;
    }

    public function combinatiosGenerator()
    {
        if (!$this->canCombine($this->listsOfValues)) {
            // Can't combine
            return;
        }

        $indexes = array_fill(0, count($this->listsOfValues), 0);

        while ($indexes) {

            yield $this->makeCombination($indexes, $this->listsOfValues);

            $indexes = $this->nextIndexes($indexes, $this->listsOfValues);
        }
    }

    protected function canCombine()
    {
        if (count($this->listsOfValues) === 0) {
            return false;
        }

        foreach ($this->listsOfValues as $values) {
            if (count($values) === 0) {
                return false;
            }
        }

        return true;
    }

    protected function hasMoreCombinations($indexes)
    {
        foreach ($indexes as $j => $i) {
            if ($i < count($this->listsOfValues[$j]) - 1) {
                return true;
            }
        }

        return false;
    }

    protected function nextIndexes($indexes)
    {
        $j = count($this->listsOfValues) - 1;

        while ($j >= 0) {
            if ($indexes[$j] < count($this->listsOfValues[$j]) - 1) {
                $indexes[$j] = $indexes[$j] + 1;
                return $indexes;
            } else {
                $indexes[$j] = 0;
            }
            $j--;
        }
    }

    protected function makeCombination($indexes)
    {
        $combination = [];

        foreach ($this->listsOfValues as $j => $values) {
            $combination[] = $values[$indexes[$j]];
        }

        return $combination;
    }
}
