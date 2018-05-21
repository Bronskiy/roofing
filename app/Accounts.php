<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use Illuminate\Support\Facades\Hash;


use Illuminate\Database\Eloquent\SoftDeletes;

class Accounts extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'accounts';

    protected $fillable = [
          'host',
          'port',
          'encryption',
          'validate_cert',
          'username',
          'password',
          'name'
    ];


    public static function boot()
    {
        parent::boot();

        Accounts::observe(new UserActionsObserver);
    }

    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        $this->attributes['password'] = encrypt($input);
    }




}
