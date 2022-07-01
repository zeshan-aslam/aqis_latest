<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOutCome extends Model
{
    use HasFactory;
    use  HasFactory;

    public $timestamps = true;

    protected $table = 'client_outcome';

    protected $fillable = [
        'category_id',
        'outcome_id',
        'client_id',
        'status'
    ];

    public function outcome()
    {
        return $this->belongsTo(Outcome::class,'outcome_id','id');
    }
}
