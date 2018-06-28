<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class Leads extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'leads';
    
    protected $fillable = [
          'lead_name',
          'lead_phones',
          'lead_time',
          'lead_roof_age',
          'lead_foor_type',
          'lead_address',
          'lead_notes',
          'inbox_id'
    ];
    

    public static function boot()
    {
        parent::boot();

        Leads::observe(new UserActionsObserver);
    }
    
    public function inbox()
    {
        return $this->hasOne('App\Inbox', 'id', 'inbox_id');
    }


    
    
    
}