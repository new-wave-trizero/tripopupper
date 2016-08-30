<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Validator;

class AgencyAccountController extends Controller
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
        $this->middleware('can:manage-agencies');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $agencyUsers = User::agency()->notMe()
            ->with('agencyAccount')->get();

        return view('agency_account.list', compact('agencyUsers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'valid_until' => 'required|date',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->sometimes('max_member_customers', 'required|integer', function ($input) {
            return $input->max_member_customers !== 'unlimited';
        });

        $this->validateWith($validator, $request);

        $agencyUser = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'account_type' => 'agency',
        ]);
        $agencyUser->agencyAccount()->create(
            $request->get('max_member_customers') === 'unlimited'
            ? $request->only('valid_until')
            : $request->only('valid_until', 'max_member_customers')
        );

        return redirect('agency-account');
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
        $agencyUser = User::agency()->notMe()->findOrFail($id);

        $agencyUser->delete();

        return redirect('agency-account');
    }
}
