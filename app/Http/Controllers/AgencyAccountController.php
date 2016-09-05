<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;

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

        $memberCustomersPackages = $this->getMemberCustomersPackages();

        return view('agency_account.list', compact('agencyUsers', 'memberCustomersPackages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $packagesValues = array_pluck($this->getMemberCustomersPackages(), 'value');
        $rules = [
            'name'                 => 'required|max:255',
            'email'                => 'required|email|max:255|unique:users',
            'password'             => 'required|min:6',
            'valid_until'          => 'required|date',
            'max_member_customers' => 'required|in:' . implode(',', $packagesValues),
        ];

        $this->validate($request, $rules);

        $agencyUser = User::create([
            'name'         => $request->get('name'),
            'email'        => $request->get('email'),
            'password'     => bcrypt($request->get('password')),
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agencyUser = User::agency()->notMe()->findOrFail($id);

        $memberCustomersPackages = $this->getMemberCustomersPackages($agencyUser);
        $popups = $agencyUser->popups;
        $customerUsers = $agencyUser->agencyAccount
            ->ownedCustomerAccounts()
            ->with('user')
            ->get()
            ->map(function ($account) { return $account->user; });

        return view('agency_account.show', compact('agencyUser', 'memberCustomersPackages', 'popups', 'customerUsers'));
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
        $agencyUser = User::agency()->notMe()->findOrFail($id);

        $packagesValues = array_pluck($this->getMemberCustomersPackages($agencyUser), 'value');
        $rules = [
            'name'                 => 'required|max:255',
            'email'                => 'required|email|max:255|unique:users,email,' . $agencyUser->id,
            'valid_until'          => 'required|date',
            'max_member_customers' => 'required|in:' . implode(',', $packagesValues),
        ];

        $this->validate($request, $rules);

        $agencyUser->fill([
            'name'  => $request->get('name'),
            'email' => $request->get('email'),
        ]);
        $agencyUser->save();

        $agencyUser->agencyAccount->fill([
            'valid_until'          => $request->get('valid_until'),
            'max_member_customers' => $request->get('max_member_customers') === 'unlimited' ? null : $request->get('max_member_customers'),
        ]);
        $agencyUser->agencyAccount->save();

        return redirect("agency-account/{$agencyUser->id}");
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

    /**
     * Get configured member customer packages, when an agencyUser
     * instance is given also check that packages are conistent with
     * current number of customer of agency
     *
     * @param  \App\User|null $agencyUser
     * @return int
     */
    protected function getMemberCustomersPackages($agencyUser = null)
    {
        $packages = config('popup.member_customers_packages');

        if (is_null($agencyUser)) {
            return $packages;
        }

        $ownedCustomersCount = $agencyUser->agencyAccount->ownedCustomerAccounts()->count();
        return collect($packages)->reject(function ($package) use ($ownedCustomersCount) {
            $value = $package['value'];
            return $value !== 'unlimited' && $value < $ownedCustomersCount;
        })
        ->all();
    }
}
