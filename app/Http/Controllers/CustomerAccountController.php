<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Gate;

class CustomerAccountController extends Controller
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
        $this->middleware('can:manage-customers', [
            'only' => ['index', 'store']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->isAgency()) {
            $customerUsers = $request->user()
                ->agencyAccount
                ->ownedCustomerAccounts()
                ->with('user')
                ->get()
                ->map(function ($account) { return $account->user; });
        } else {
            $customerUsers = User::noAgencyCustomer()->notMe()->get();
        }

        return view('customer_account.list', compact('customerUsers'));
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
            'can_delete_popups' => 'required|boolean',
            'can_create_popups' => 'required|boolean',
            'can_update_popups_domains' => 'required|boolean',
        ];

        if ($request->user()->isAdmin()) {
            $rules['valid_until'] = 'required|date';
        }

        $this->validate($request, $rules);

        $customerUser = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'account_type' => 'customer',
        ]);

        $customerAccount = $customerUser->customerAccount()->create(
            array_merge($request->only('can_delete_popups', 'can_create_popups',
                'can_update_popups_domains'
            ), $request->user()->isAdmin() ? $request->only('valid_until') : [])
        );

        if ($request->user()->isAgency()) {
            $customerAccount
                ->membershipAgencyAccount()
                ->associate($request->user()->agencyAccount);
            $customerAccount->save();
        }

        return redirect('customer-account');
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
        $customerUser = User::customer()->notMe()->findOrFail($id);

        $this->authorize('manage-customer', $customerUser);

        $customerUser->delete();

        return redirect('customer-account');
    }
}
