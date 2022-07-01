<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientmeeting extends Model
{
    protected $fillable = [
        'client_id',
    	'programName',
    	'serviceProvided',
    	'type',
    	'date',
    	'status',
    	'serviceDelivery',
    	'location',
    	'funder',
    	'staff_id',
        'notes',
        'meetingLink',
        'duration'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class)->with('user');
    }

    public function staff()
    {
        return $this->belongsTo('App\Staff')->with('user');
    }

    function clientaps()
    {
        return $this->hasMany(Clientap::class, 'clientmeeting_id')->with('CurrentNoc');
    }
    public function questions()
    {
        return $this->hasMany(ClientQuestion::class, 'clientmeeting_id')->with('feedback');
    }
}
