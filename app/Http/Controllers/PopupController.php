<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\AwesomeNamesMaker\AwesomeNamesMaker;
use App\Popup;

class PopupController extends Controller
{

    protected $nameMaker;

    public function __construct(AwesomeNamesMaker $nameMaker)
    {
        $this->nameMaker = $nameMaker;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showPopupsList()
    {
        return view('popup.list', [
            'popups' => Popup::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function showPoupDetail($name)
    {
        return view('popup.detail', [
            'name'  => $name,
            'popup' => Popup::whereName($name)->first(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function suggestPopupName()
    {
        do {
            $name = $this->nameMaker->makeAwesomeName();
        } while(! is_null(Popup::where('name', $name)->first()));

        return redirect("popup/{$name}");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePopup(Request $request)
    {
        $this->validate($request, [
            'name'   => ['required', 'max:100', 'unique:popups', 'regex:/'.Popup::NAME_REGEX.'/'],
            'config' => 'required|json',
            'domain' => 'required|max:255',
        ]);

        $popup = new Popup($request->only('domain', 'name'));
        $popup->config = (object)json_decode($request->get('config'), true);
        $popup->save();

        return redirect("popup/{$popup->name}");
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        dd(Popup::where('name', $name)->firstOrFail()->config);
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
