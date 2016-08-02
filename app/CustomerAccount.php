<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CustomerAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'can_delete_popups', 'can_create_popups', 'can_update_popups_domains',
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
        if (is_null($this->membership_agency_account_id)) {
            return $this->valid_until->lt(Carbon::now()->setTime(0, 0, 0));
        }

        return $this->membershipAgencyAccount->isExpired();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function membershipAgencyAccount()
    {
        return $this->belongsTo('App\AgencyAccount');
    }

}
