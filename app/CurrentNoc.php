<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentNoc extends Model
{
    use HasFactory;

    protected $table ="noc_2021";

    public function aptemplate()
    {
        return $this->hasOne(Aptemplate::class,'current_noc')->with('updatedBy');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function clientap()
    {
        return $this->hasOne(Clientap::class,'current_noc');
    }
}
