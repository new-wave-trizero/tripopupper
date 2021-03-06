<?php

return [

    // Regex of popup name
    'regex' =>  '^[a-zA-Z]+[a-zA-Z0-9]*(-[a-zA-Z0-9]+)*$',

    // Max len of popup name
    'maxlen' => 100,

    // Reserved popup names
    'reserverd' => [
        'trizero',
        'popup',
    ],

    // Url of client js lib
    'js_lib_url' => env('TRIPOPUPPER_JS_SDK', 'https://rawgit.com/new-wave-trizero/tripopupper-js/master/lib/tripopupper.js'),

    // Images config
    'images' => [
        // Take the last N
        'take_last' => 10,
    ],

    'member_customers_packages' => [
        [
            'name'  => 50,
            'value' => 50,
        ],
        [
            'name'  => 100,
            'value' => 100,
        ],
        [
            'name'  => 'Illimitato',
            'value' => 'unlimited',
        ],
    ],

    // The suggestor config
    'suggestor' => [
        'groups' => [
            [
                'name' => 'pokemons',
                'lists' => [
                    [
                        'name' => 'color',
                        'source' => 'http://pokeapi.co/api/v2/pokemon-color?limit=1000',
                        'path' => 'results',
                        'field' => 'name'
                    ],
                    [
                        'name' => 'nature',
                        'source' => 'http://pokeapi.co/api/v2/nature?limit=1000',
                        'path' => 'results',
                        'field' => 'name'
                    ],
                    [
                        'name' => 'pokemon',
                        'source' => 'http://pokeapi.co/api/v2/pokemon?limit=1000',
                        'path' => 'results',
                        'field' => 'name'
                    ]
                ]
            ],
            //[
                //'name' => 'awesomes',
                //'lists' => [
                    //[
                        //'name' => 'awesome',
                        //'source' => 'https://gist.githubusercontent.com/gffuma/7125c745902b0d934c446e17d578695a/raw/9c4465118537ddf0ecfb19c64ce88bd6b9b9ce96/awesomes.json',
                    //],
                //]
            //],
        ]
    ],
];
