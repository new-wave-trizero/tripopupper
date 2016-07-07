<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Gate;
use App\Http\Requests;
use App\Services\AwesomeNamesMaker\AwesomeNamesMaker;
use App\Popup;

class PopupController extends Controller
{
    /**
     * Make awesome names
     *
     * @var \App\Services\AwesomeNamesMaker\AwesomeNamesMaker
     */
    protected $nameMaker;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\AwesomeNamesMaker\AwesomeNamesMaker $nameMaker
     * @return void
     */
    public function __construct(AwesomeNamesMaker $nameMaker)
    {
        $this->nameMaker = $nameMaker;
        $this->middleware('auth');
    }

    /**
     * Display a listing of popups.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showPopupsList(Request $request)
    {
        $popups = $request->user()
            ->popups()->orderBy('id', 'desc')->get();

        return view('popup.list', compact('popups'));
    }

    /**
     * Validation rule of popup name.
     *
     * @return string
     */
    protected function popupNameRule()
    {
        return [
            'required',
            'max:' . config('popup.maxlen'),
            'unique:popups',
            'regex:/' . config('popup.regex') . '/',
            'not_in:' . implode(config('popup.reserverd'), ','),
        ];
    }

    /**
     * Suggest an awesome new popup name.
     *
     * @return string
     */
    protected function suggestPopupName()
    {
        do {
            $name = $this->nameMaker->makeAwesomeName();
        } while(! is_null(Popup::whereName($name)->first()));

        return $name;
    }

    /**
     * Store a newly created popup.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePopup(Request $request)
    {
        if (!$request->has('name') || $request->get('name') === '') {
            // When name is not given, we generate a random awesome name on the fly!
            $name = $this->suggestPopupName();
        } else {
            // Otherwise check and use the given user name
            $this->validate($request, [
                'name' => $this->popupNameRule()
            ]);
            $name = $request->get('name');
        }

        $popup = $request->user()->popups()->create([
            'name'   => $name,
            'config' => (object)[], // aka {}
        ]);

        return redirect("popup/{$name}");
    }

    /**
     * Edit popup.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function editPoup($name)
    {
        $popup = Popup::whereName($name)->firstOrFail();

        // Can't edit popup
        if (Gate::denies('manage-popup', $popup)) {
            abort(403);
        }

        return view('popup.edit', compact('popup'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //public function storePopup(Request $request)
    //{
        //$this->validate($request, [
            //'name'   => ['required', 'max:100', 'unique:popups', 'regex:/'.Popup::NAME_REGEX.'/'],
            //'config' => 'required|json',
            //'domain' => 'required|max:255',
        //]);

        //$popup = new Popup($request->only('domain', 'name'));
        //$popup->config = (object)json_decode($request->get('config'), true);
        //$request->user()->popups()->save($popup);

        //return redirect("popup/{$popup->name}");
    //}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    ////public function update(Request $request, $id)
    ////{
        //////
    ////}

    /**
     * Remove popup.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function destroyPopup($name)
    {
        $popup = Popup::whereName($name)->first();

        // Poup doesn't exist anymore, back to home...
        if (is_null($popup)) {
            return redirect('/');
        }

        // Can't remove popup
        if (Gate::denies('manage-popup', $popup)) {
            abort(403);
        }

        $popup->delete();

        return redirect('/');
    }
}
