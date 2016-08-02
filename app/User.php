<?php

namespace App;

use Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'account_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeAdmin($query)
    {
        return $query->where('account_type', 'admin');
    }

    public function scopeCustomer($query)
    {
        return $query->where('account_type', 'customer');
    }

    public function scopeNoAgencyCustomer($query)
    {
        return $query
            ->where('account_type', 'customer')
            ->whereHas('customerAccount', function ($query) {
                $query->where('membership_agency_account_id', null);
            });
    }

    public function scopeAgency($query)
    {
        return $query->where('account_type', 'agency');
    }

    public function scopeNotMe($query)
    {
        return $query->where('id', '<>', Auth::user()->id);
    }

    public function isAdmin()
    {
        return $this->account_type === 'admin';
    }

    public function isCustomer()
    {
        return $this->account_type === 'customer';
    }

    public function isAgency()
    {
        return $this->account_type === 'agency';
    }

    public function popups()
    {
        return $this->hasMany('App\Popup');
    }

    public function agencyAccount()
    {
        return $this->hasOne('App\AgencyAccount');
    }

    public function customerAccount()
    {
        return $this->hasOne('App\CustomerAccount');
    }
}
