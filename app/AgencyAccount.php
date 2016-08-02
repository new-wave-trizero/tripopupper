<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AgencyAccount extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'valid_until',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['valid_until'];

    public function isExpired()
    {
        return $this->valid_until->lt(Carbon::now()->setTime(0, 0, 0));
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function ownedCustomerAccounts()
    {
        return $this->hasMany('App\CustomerAccount', 'membership_agency_account_id');
    }
}
