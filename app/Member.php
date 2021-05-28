<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = array(
        'user_id', 'name','spaciality','image','description','address'
  );
 

  public function user()
  {
      return $this->belongsTo('App\User');
  }
}
