<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    const NAME_REGEX = '^(?=[a-z]+(-[a-z]+)*$)(.{1,100})$';

    protected $fillable = [
        'domain',
        'name',
    ];

    protected $casts = [
        'config' => 'array',
    ];
}
