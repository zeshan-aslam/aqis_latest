<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'groupActivity_id',
        'groupId',
        'allDay',
        'start',
        'end',
        'title',
        'type',
        'backgroundColor',
        'borderColor',
        'textColor',
    ];

    public function groupActivity()
    {
        return $this->belongsTo(GroupActivity::class, 'groupActivity_id', 'id');
    }

    public function attendances()
    {
        return $this->belongsToMany(Client::class,'client_event');
    }
}
