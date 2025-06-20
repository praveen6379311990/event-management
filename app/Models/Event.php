<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','start_date','end_date','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function attendees(){
        return $this->hasMany(Attendee::class,'event_id');
    }
}
