<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientQuestion extends Model
{
    use HasFactory;
    protected $table="client_questions";
    public function feedback()
    {
        return $this->belongsTo(Feedback::class,'feedback_id');
    }
}
