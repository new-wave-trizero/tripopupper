<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;

class AdminAccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('account.expiration');
        $this->middleware('can:manage-admins');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $adminUsers = User::admin()->notMe()->get();

        return view('admin_account.list', compact('adminUsers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'account_type' => 'admin',
        ]);

        return redirect('admin-account');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $adminUser = User::admin()->notMe()->findOrFail($id);

        $popups = $adminUser->popups;

        return view('admin_account.show', compact('adminUser', 'popups'));
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
        $adminUser = User::admin()->notMe()->findOrFail($id);

        $rules = [
            'name'  => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $adminUser->id,
        ];

        $this->validate($request, $rules);

        $adminUser->fill([
            'name'  => $request->get('name'),
            'email' => $request->get('email'),
        ]);
        $adminUser->save();

        return redirect("admin-account/{$adminUser->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $adminUser = User::admin()->notMe()->findOrFail($id);

        $adminUser->delete();

        return redirect('admin-account');
    }
}
