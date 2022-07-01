<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'user_id',
        'client_id',

    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
