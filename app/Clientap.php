<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientap extends Model
{
    protected $fillable = [
        'aptemplate_id',
        'client_id',
        'clientmeeting_id',
        'staff_id',
        'noc',
        'current_noc',
        'credAssessment',
        'certLicense',
        'docTranslation',
        'training',
        'localExperience',
        'altCareers',
        'mentoring',
        'portfolioWorkshop',
        'otherResources',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class)->with('user');
    }
    public function staff()
    {
        return $this->belongsTo('App\Staff')->with('user');
    }
    function meeting()
    {
        return $this->belongsTo(Clientmeeting::class);
    }

    function facilitator()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    function referrals()
    {
        return $this->belongsToMany(Referral::class)->withPivot('category');
    }

    function template()
    {
        return $this->hasOne(Aptemplate::class);
    }

    public function currentNoc()
	{
		return $this->belongsTo(CurrentNoc::class, 'current_noc');
	}
}
