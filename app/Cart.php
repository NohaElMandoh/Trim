<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    

    protected $table = 'carts';
    public $timestamps = true;


    protected $fillable = array(
          'user_id', 'state','quantity','note','price','product_id','product_type'
    );
   

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function product()
    {
        // return $this->belongsTo('App\Models\Item');
        return $this->morphTo();

    }

 

  
}
