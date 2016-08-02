<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Storage;
use App\Popup;
use App\User;

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

            // When agency remove, related users and popups dirs
            if ($user->isAgency()) {
                $customerUserIds = $user->agencyAccount->ownedCustomerAccounts()->pluck('user_id');
                Popup::whereIn('user_id', $customerUserIds->all())->pluck('name')->each(function ($name) {
                    Storage::disk('public')->deleteDirectory("popup/{$name}");
                });
                User::whereIn('id', $customerUserIds->all())->delete();
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
