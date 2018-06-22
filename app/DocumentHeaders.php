<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentHeaders extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'documentheaders';
    
    protected $fillable = [
          'header_title',
          'header_top',
          'header_bottom'
    ];
    

    public static function boot()
    {
        parent::boot();

        DocumentHeaders::observe(new UserActionsObserver);
    }
    
    
    
    
}