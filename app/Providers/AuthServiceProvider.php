<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        // Admin can do all the stuff
        $gate->before(function ($user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        $gate->define('login-as-another-user', function ($user, $anotherUser) {
            // Customer can't login as...
            if ($user->isCustomer()) {
                return false;
            }

            // Can login as only to a customer...
            if (!$anotherUser->isCustomer()) {
                return false;
            }

            // Is a customer owned by current agency
            return $anotherUser->customerAccount->membership_agency_account_id === $user->agencyAccount->id;
        });

        // Only admins can manage admins
        $gate->define('manage-admins', function ($user) {
            return false;
        });

        // Only admins can manage agencies
        $gate->define('manage-agencies', function ($user) {
            return false;
        });

        $gate->define('manage-customers', function ($user) {
            return $user->isAgency();
        });

        $gate->define('manage-customer', function ($user, $customerUser) {
            // Customer can't manage customer...
            if ($user->isCustomer()) {
                return false;
            }

            // Current agency owned given customer
            return $customerUser->customerAccount->membership_agency_account_id === $user->agencyAccount->id;
        });

        $gate->define('view-popup', function ($user, $popup) {
            // Popup is owned directly by user...
            if ($user->id === $popup->user_id) {
                return true;
            }

            // Customer can manage only theirs popups...
            if ($user->isCustomer()) {
                return false;
            }

            // User of popup is not a customer, can't show...
            if (!$popup->user->isCustomer()) {
                return false;
            }

            // Popup is owed by a customer owned by current agency
            return $popup->user->customerAccount->membership_agency_account_id === $user->agencyAccount->id;
        });

        $gate->define('create-popups', function ($user) {
            // User is a customer and can't create popups
            if ($user->isCustomer() && !$user->customerAccount->can_create_popups) {
                return false;
            }

            return true;
        });

        $gate->define('update-popup', function ($user, $popup) {
            // Popup is owned directly by user...
            if ($user->id === $popup->user_id) {
                return true;
            }

            // Customer can manage only theirs popups...
            if ($user->isCustomer()) {
                return false;
            }

            // User of popup is not a customer, can't update...
            if (!$popup->user->isCustomer()) {
                return false;
            }

            // Popup is owed by a customer owned by current agency
            return $popup->user->customerAccount->membership_agency_account_id === $user->agencyAccount->id;
        });

        $gate->define('update-popup-domain', function ($user, $popup) {
            // Popup is owned directly by user...
            if ($user->id === $popup->user_id) {
                // Popup is owned by customer but customer can't update
                // poup domais...
                if ($user->isCustomer() && !$user->customerAccount->can_update_popups_domains) {
                    return false;
                }
                return true;
            }

            // Customer can manage only theirs popups...
            if ($user->isCustomer()) {
                return false;
            }

            // User of popup is not a customer, can't update domain...
            if (!$popup->user->isCustomer()) {
                return false;
            }

            // Popup is owed by a customer owned by current agency
            return $popup->user->customerAccount->membership_agency_account_id === $user->agencyAccount->id;
        });

        $gate->define('delete-popup', function ($user, $popup) {
            // Popup is owned directly by user...
            if ($user->id === $popup->user_id) {
                // Popup is owned by customer but customer can't
                // delete popups...
                if ($user->isCustomer() && !$user->customerAccount->can_delete_popups) {
                    return false;
                }
                return true;
            }

            // Customer can manage only theirs popups...
            if ($user->isCustomer()) {
                return false;
            }

            // User of popup is not a customer, can't delete domain...
            if (!$popup->user->isCustomer()) {
                return false;
            }

            // Popup is owed by a customer owned by current agency
            return $popup->user->customerAccount->membership_agency_account_id === $user->agencyAccount->id;
        });

        $gate->define('share-popup', function ($user, $popup) {
            return false;
        });
    }
}
