<?php

return [

    // Regex of popup name
    'regex' =>  '^(?=[a-zA-Z]+(-[a-zA-Z]+)*$)(.{1,100})$',

    // Max len of popup name
    'maxlen' => 100,

    // Reserved popup names
    'reserverd' => [
        'trizero',
        'popup',
    ],

    // Url of client js lib
    'js_lib_url' => 'https://rawgit.com/new-wave-trizero/tripopupper-js/master/lib/tripopupper.js',

];
