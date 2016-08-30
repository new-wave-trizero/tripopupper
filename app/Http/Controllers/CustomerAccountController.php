<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Gate;
use Validator;

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
        $user = $request->user();
        $rules = [
            'name'                      => 'required|max:255',
            'email'                     => 'required|email|max:255|unique:users',
            'password'                  => 'required|min:6',
            'valid_until'               => 'required|date',
            'can_delete_popups'         => 'required|boolean',
            'can_create_popups'         => 'required|boolean',
            'can_update_popups_domains' => 'required|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->after(function($validator) use ($user) {
            if ($user->isAgency() && !$user->agencyAccount->canOwnMoreCustomers()) {
                $validator->errors()->add('max_member_customers', 'Hai superato il numero massimo di clienti.');
            }
        });

        $this->validateWith($validator, $request);

        $customerUser = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'account_type' => 'customer',
        ]);

        $customerAccount = $customerUser->customerAccount()->create($request->only(
            'can_delete_popups', 'can_create_popups', 'can_update_popups_domains',
            'valid_until'
        ));

        if ($user->isAgency()) {
            $customerAccount
                ->membershipAgencyAccount()
                ->associate($user->agencyAccount);
            $customerAccount->save();
        }

        return redirect('customer-account');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customerUser = User::customer()->notMe()->findOrFail($id);

        $this->authorize('manage-customer', $customerUser);

        $popups = $customerUser->popups;

        return view('customer_account.show', compact('customerUser', 'popups'));
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
        $customerUser = User::customer()->notMe()->findOrFail($id);

        $this->authorize('manage-customer', $customerUser);

        $rules = [
            'name'                      => 'required|max:255',
            'email'                     => 'required|email|max:255|unique:users,email,' . $customerUser->id,
            'valid_until'               => 'required|date',
            'can_delete_popups'         => 'required|boolean',
            'can_create_popups'         => 'required|boolean',
            'can_update_popups_domains' => 'required|boolean',
        ];

        $this->validate($request, $rules);

        $customerUser->fill([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'account_type' => 'customer',
        ]);
        $customerUser->save();

        $customerUser->customerAccount->fill($request->only(
            'can_delete_popups', 'can_create_popups', 'can_update_popups_domains',
            'valid_until'
        ));
        $customerUser->customerAccount->save();

        return redirect("customer-account/{$customerUser->id}");
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
