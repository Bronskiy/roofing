<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class Inbox extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'inbox';
    
    protected $fillable = [
          'inbox_sender',
          'inbox_date',
          'inbox_subject',
          'inbox_text_body',
          'inbox_html_body',
          'inbox_edited_body',
          'inbox_leads_count'
    ];
    

    public static function boot()
    {
        parent::boot();

        Inbox::observe(new UserActionsObserver);
    }
    
    
    
    
}