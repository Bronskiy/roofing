<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;

use Carbon\Carbon; 

use Illuminate\Database\Eloquent\SoftDeletes;

class InboxSettings extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'inboxsettings';
    
    protected $fillable = [
          'accounts_id',
          'inbox_settings_sender',
          'inbox_settings_date'
    ];
    

    public static function boot()
    {
        parent::boot();

        InboxSettings::observe(new UserActionsObserver);
    }
    
    public function accounts()
    {
        return $this->hasOne('App\Accounts', 'id', 'accounts_id');
    }


    
    /**
     * Set attribute to date format
     * @param $input
     */
    public function setInboxSettingsDateAttribute($input)
    {
        if($input != '') {
            $this->attributes['inbox_settings_date'] = Carbon::createFromFormat(config('quickadmin.date_format'), $input)->format('Y-m-d');
        }else{
            $this->attributes['inbox_settings_date'] = '';
        }
    }

    /**
     * Get attribute from date format
     * @param $input
     *
     * @return string
     */
    public function getInboxSettingsDateAttribute($input)
    {
        if($input != '0000-00-00') {
            return Carbon::createFromFormat('Y-m-d', $input)->format(config('quickadmin.date_format'));
        }else{
            return '';
        }
    }


    
}