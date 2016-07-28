<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;
use Gate;
use App\Http\Requests;
use App\Services\AwesomeNamesSuggestor\AwesomeNamesSuggestor;
use App\Popup;

class PopupController extends Controller
{
    /**
     * Make awesome names
     *
     * @var \App\Services\AwesomeNamesSuggestor\Suggestor
     */
    protected $namesSuggestor;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\AwesomeNamesSuggestor\AwesomeNamesSuggestor $awesomeNamesSuggestor
     * @return void
     */
    public function __construct(AwesomeNamesSuggestor $awesomeNamesSuggestor)
    {
        $this->namesSuggestor = $awesomeNamesSuggestor->suggestor(config('popup.suggestor'));
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
     * @return string|null
     */
    protected function suggestPopupName()
    {
        try {
            return $this->namesSuggestor->suggestFreshRandomName(function ($name) {
                return Popup::whereName($name)->count() === 0; // A new name is always fresh!
            });
        } catch (\Exception $e) {
            // TODO: Better error handling...
            return null;
        }
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
            'domain'  => 'max:100',
        ]);

        $popup->fill($request->only('domain'));
        $popup->config = json_decode($request->get('config'), true);
        $popup->save();

        return redirect("popup/{$popup->name}");
    }

    /**
     * Upload image related to popup.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $name
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(Request $request, $name)
    {
        $popup = Popup::whereName($name)->firstOrFail();

        // Can't upload image related to popup
        $this->authorize('manage-popup', $popup);

        $this->validate($request, [
            'image'  => 'required|image',
        ]);

        // Use public disk
        $disk = Storage::disk('public');

        // TODO: divide et impera
        // Write file with an unique random name
        $stream = fopen($request->file('image')->getRealPath(), 'r+');
        $fileName = str_random(10) . '-' . microtime(true) . '.' . $request->file('image')->guessExtension();
        $disk->writeStream("popup/{$popup->name}/${fileName}", $stream);
        fclose($stream);

        // Take only the last N upload images...
        $take = config('popup.images.take_last');
        $images =
            // List files in popup directory
            collect($disk->files("popup/{$popup->name}"))
            // Take only the images
            ->filter(function ($file) use ($disk) {
                return substr($disk->getMimeType($file), 0, 5) == 'image';
            })
            // Order files by timestamp
            ->sortBy(function ($file) {
                return (int)explode('-', pathinfo($file)['filename'])[1];
            })
            // [un, dos, tres] no broken keys
            ->values();

        // Take the last N
        $lastNImages = $images->take(-$take);

        // TODO: Make the images related to popup configurable...
        // Push current selected images to taken images...
        if (!is_null($popup->config) && isset($popup->config['imageUrl'])) {
            $lastNImages->push(str_replace(asset('/storage').'/', '', (string)$popup->config['imageUrl']));
        }

        // Mantein only the last N uploaded images and current images
        $disk->delete($images->diff($lastNImages)->all());

        // Response different by request
        if ($request->wantsJson()) {
            return response()->json(asset("storage/popup/{$popup->name}/{$fileName}"));
        }
        return redirect("popup/{$popup->name}");
    }

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

        // Delete popup upload dir
        Storage::disk('public')->deleteDirectory("popup/{$popup->name}");

        return redirect('/');
    }
}
