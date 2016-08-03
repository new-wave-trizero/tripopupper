<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PopupsCompositionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-agencies');
    }

    /**
     * Show popups composition page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showPopupsComposition(Request $request)
    {
        $popups = $request->user()->popups;
        return view('popup_composition', compact('popups'));
    }
}
