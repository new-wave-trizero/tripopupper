<?php

namespace App\Services\AwesomeNamesMaker;

use Illuminate\Cache\Repository as LaravelCache;

#TODO: Rename to awesome name suggert or something like that
class AwesomeNamesMaker
{
    public function __construct(LaravelCache $cache)
    {
        $this->cache = $cache;
    }

    public function makeAwesomeName()
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'http://pokeapi.co/api/v2/']);

    }

    public function makeAwesomeNamePokemon()
    {
        list($pokemons, $colors, $natures) = $this->cache->rememberForever('awesome-stuff', function() {
            return $this->getAwesomeStuff();
        });

        $randomColor = $colors[rand(0, count($colors) - 1)];
        $randomNature = $natures[rand(0, count($natures) - 1)];
        $randomPokemon = $pokemons[rand(0, count($pokemons) - 1)];

        return $randomColor . '-' . $randomNature . '-' . $randomPokemon;
    }

    protected function getAwesomeStuff()
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'http://pokeapi.co/api/v2/']);

        // List ok pokemons
        $pokemons = collect(json_decode(
            $client->request('GET', 'pokemon?limit=1000')->getBody(), true
        )['results'])->pluck('name')->all();

        // List of pokemon colors
        $colors = collect(json_decode(
            $client->request('GET', 'pokemon-color?limit=1000')->getBody(), true
        )['results'])->pluck('name')->all();

        // List of pokemon natures
        $natures = collect(json_decode(
            $client->request('GET', 'nature?limit=1000')->getBody(), true
        )['results'])->pluck('name')->all();

        return [$pokemons, $colors, $natures];
    }
}


