<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class Mailbox extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'mailbox';
    
    protected $fillable = [
          'from_name',
          'from_email',
          'subject',
          'mail_body',
          'export_check'
    ];
    

    public static function boot()
    {
        parent::boot();

        Mailbox::observe(new UserActionsObserver);
    }
    
    
    
    
}