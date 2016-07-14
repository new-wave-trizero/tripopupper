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

        $suggestedName = $this->suggestPopupName();

        return view('popup.list', compact('popups', 'suggestedName'));
    }

    /**
     * Validation rule of popup name.
     *
     * @param  \App\Popup $popup
     * @return string
     */
    protected function popupNameRule($popup = null)
    {
        return [
            'required',
            'max:' . config('popup.maxlen'),
            'unique:popups,name' . is_null($popup) ? '' : ',' . $popup->id,
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
        $this->validate($request, [
            'name' => $this->popupNameRule()
        ]);

        $popup = $request->user()->popups()->create([
            'name'   => $request->get('name'),
            'config' => (object)[], // aka {}
        ]);

        return redirect("popup/{$popup->name}");
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
        $this->authorize('manage-popup', $popup);

        return view('popup.edit', compact('popup'));
    }

    /**
     * Update popup.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function updatePopup(Request $request, $name)
    {
        $popup = Popup::whereName($name)->firstOrFail();

        // Can't update popup
        $this->authorize('manage-popup', $popup);

        $this->validate($request, [
            'config'  => 'required|json',
            'domain'  => 'required|max:100',
        ]);

        $popup->fill($request->only('domain'));
        $popup->config = json_decode($request->get('config'), true);
        $popup->save();

        return redirect("popup/{$popup->name}");
    }

    // POST /popup/{name}/upload-image
    //public function uploadImage(Request $request, $name)
    //{
        //$popup = Popup::whereName($name)->firstOrFail();

        //// Can't upload image related to popup
        //$this->authorize('manage-popup', $popup);

    //}

    /**
     * Remove popup.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function destroyPopup($name)
    {
        $popup = Popup::whereName($name)->firstOrFail();

        // Can't remove popup
        $this->authorize('manage-popup', $popup);

        $popup->delete();

        return redirect('/');
    }
}
