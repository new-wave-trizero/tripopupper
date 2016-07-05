<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\AwesomeNamesMaker\AwesomeNamesMaker;

class PopupController extends Controller
{
    protected $nameMaker;

    public function __construct(AwesomeNamesMaker $nameMaker)
    {
        $this->nameMaker = $nameMaker;
    }

    public function showPopup()
    {
        dd($this->nameMaker->makeAwesomeName());
    }
}
