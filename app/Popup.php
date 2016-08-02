<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Popup extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $fillable = [
        'domain',
        'name',
        'config',
        'secret',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'secret', 'remember_token',
    ];

    public function isRelatedAccountExpired()
    {
        if ($this->user->isAgency() && $this->user->agencyAccount->isExpired()) {
            return true;
        }

        if ($this->user->isCustomer() && $this->user->customerAccount->isExpired()) {
            return true;
        }

        return false;
    }

    /**
     * Secret is more cool then password.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->secret;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
