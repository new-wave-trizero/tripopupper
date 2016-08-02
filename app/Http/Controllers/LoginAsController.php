<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\User;

class LoginAsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Login as user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function loginAs(Request $request, $id)
    {
        $user = $request->user();
        $anotherUser = User::findOrFail($id);

        if ($user->id === $anotherUser->id) {
            return back();
        }

        $this->authorize('login-as-another-user', $anotherUser);

        Auth::loginUsingId($id);

        return redirect('/');
    }
}
