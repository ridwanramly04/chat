<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
      'sender_id',
      'recipient_id'  
    ];

    public function sender(){
        return $this->belongsTo('App\User', 'sender_id');
    }

    public function recipient(){
        return $this->belongsTo('App\User', 'recipient_id');
    }
    public function message(){
        return $this->hasMany('App\Message');
    }
}
