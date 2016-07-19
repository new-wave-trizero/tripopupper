<?php

namespace App\Services\AwesomeNamesSuggestor;

use Illuminate\Cache\Repository as LaravelCache;

class Suggestor
{
    protected $cache;

    protected $config;

    public function __construct(LaravelCache $cache, $config)
    {
        $this->cache = $cache;
        $this->config = $config;
    }

    public function getGroupSuggesstions()
    {
        return collect($this->config['groups'])->pluck('name')->all();
    }

    public function getAllSuggestionsNames()
    {
        $names = [];
        foreach ($this->config['groups'] as $group) {
            foreach ($this->makeCombinationsGeneratorOfGroup($group) as $combination) {
                $names[] = $this->combinationToName($combination);
            }
        }
        return $names;
    }

    public function getGroupSuggesstionsNames($groupName)
    {
        $group = $this->groupByName($groupName);
        $names = [];
        foreach ($this->makeCombinationsGeneratorOfGroup($group) as $combination) {
            $names[] = $this->combinationToName($combination);
        }
        return $names;
    }

    public function suggestRandomName()
    {
        return $this->suggestRandomNameOfGroup($this->randomGroup());
    }

    public function suggestGroupRandomName($groupName)
    {
        return $this->suggestRandomNameOfGroup($this->groupByName($groupName));
    }

    public function suggestFreshRandomName($isFresh)
    {
        $groups = collect($this->config['groups'])->shuffle()->all();

        foreach ($groups as $group) {
            foreach ($this->makeRandomCombinationsGeneratorOfGroup($group) as $combination) {
                $name = $this->combinationToName($combination);

                if ($isFresh($name)) {
                    return $name;
                }
            }
        }

        // TODO: Custom exception
        throw new \Exception('No fresh names....');
    }

    public function suggestGroupFreshRandomName($groupName, $isFresh)
    {
        $group = $this->groupByName($groupName);

        foreach ($this->makeRandomCombinationsGeneratorOfGroup($group) as $combination) {
            $name = $this->combinationToName($combination);

            if ($isFresh($name)) {
                return $name;
            }
        }

        // TODO: Custom exception
        throw new \Exception('No fresh names....');
    }

    protected function randomGroup()
    {
        return collect($this->config['groups'])->random();
    }

    protected function groupByName($groupName)
    {
        // TODO: Exception when invalid name
        return collect($this->config['groups'])->where('name', $groupName)->first();
    }

    protected function suggestRandomNameOfGroup($group)
    {
        $randomCombination = $this->makeRandomCombinationsGeneratorOfGroup($group)->current();
        return $this->combinationToName($randomCombination);
    }

    protected function makeCombinationsGeneratorOfGroup($group)
    {
        $listsOfValues = $this->getListsOfValuesOfGroup($group);
        return $this->makeCombinationsGenerator($listsOfValues);
    }

    protected function makeRandomCombinationsGeneratorOfGroup($group)
    {
        $listsOfValues = $this->getListsOfValuesOfGroup($group);
        return $this->makeRandomCombinationsGenerator($listsOfValues);
    }

    protected function getListsOfValuesOfGroup($group)
    {
        return collect($group['lists'])->map(function ($list) use ($group) {
            $cacheKey = $group['name'] . '.' . $list['name'];
            return $this->cache->rememberForever($cacheKey, function () use ($list) {
                return $this->getValuesOfList($list);
            });
        })->all();
    }

    protected function getValuesOfList($list)
    {
        // TODO: Exception when failed
        $source = $list['source'];

        if (is_array($source)) {
            $values = array_values($source);
        } else {
            $values = json_decode(file_get_contents($source), true);
        }

        if (isset($list['path'])) {
            $values = array_get($values, $list['path']);
        }

        if (isset($list['field'])) {
            $values = array_pluck($values, $list['field']);
        }

        return $values;
    }

    protected function combinationToName($combination)
    {
        return implode($combination, '-');
    }

    protected function makeCombinationsGenerator($listsOfValues)
    {
        $combinator = new Combinator($listsOfValues);
        return $combinator->combinatiosGenerator();
    }

    protected function makeRandomCombinationsGenerator($listsOfValues)
    {
        $combinator = new Combinator(array_map(function ($values) {
            return collect($values)->shuffle()->all();
        }, $listsOfValues));
        return $combinator->combinatiosGenerator();
    }
}
