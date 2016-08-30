<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Storage;
use App\Popup;
use App\User;
use App\CustomerAccount;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Popup::deleting(function ($popup) {
            // Delete popup upload dir
            Storage::disk('public')->deleteDirectory("popup/{$popup->name}");
        });

        User::deleting(function ($user) {
            // Delete user popups dirs
            $user->popups()->pluck('name')->each(function ($name) {
                Storage::disk('public')->deleteDirectory("popup/{$name}");
            });

            // When agency is deleted, delete related users and popups dirs
            if ($user->isAgency()) {
                $customerUserIds = $user->agencyAccount->ownedCustomerAccounts()->pluck('user_id');
                Popup::whereIn('user_id', $customerUserIds->all())->pluck('name')->each(function ($name) {
                    Storage::disk('public')->deleteDirectory("popup/{$name}");
                });
                User::whereIn('id', $customerUserIds->all())->delete();
            }
        });

        User::deleted(function ($user) {
            // When is customer materialize member_customers_count on
            // AgencyAccount model...
            if ($user->isCustomer() && $user->customerAccount->isAgencyMember()) {
                $agency = $user->customerAccount->membershipAgencyAccount;
                $agency->member_customers_count = $agency->ownedCustomerAccounts()->count();
                $agency->save();
            }
        });

        CustomerAccount::saved(function ($customerAccount) {
            // Materialize count on agency, isDirty is used because the agency
            // will attached after the account creation...
            if ($customerAccount->isDirty('membership_agency_account_id')) {
                if ($customerAccount->isAgencyMember()) {
                    $agency = $customerAccount->membershipAgencyAccount;
                    $agency->member_customers_count = $agency->ownedCustomerAccounts()->count();
                    $agency->save();
                }
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
