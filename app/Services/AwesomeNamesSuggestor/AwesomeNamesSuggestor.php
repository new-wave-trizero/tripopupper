<?php

namespace App\Services\AwesomeNamesSuggestor;

use Illuminate\Cache\Repository as LaravelCache;

class AwesomeNamesSuggestor
{
    public function __construct(LaravelCache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Make a suggestor
     *
     * @param  array $config
     * @return \App\Services\Suggestor
     */
    public function suggestor($config)
    {
        return new Suggestor($this->cache, $config);
    }
}
