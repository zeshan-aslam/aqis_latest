<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Aptemplate extends Model
{
    use SearchableTrait, HasFactory;

    protected $fillable = [
				'notes',
				'user_id',
				'noc',
                'current_noc',
                'prev_noc',
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

    protected $searchable = [
        'columns' => [
            'aptemplates.noc' => 25,
        ]
    ];

//	public function scopeSearch($query, $string)
//    {
//        $resultString = explode(" ", $string);
//        // if ($keyword!='') {
//		foreach ($resultString as $keyword) {
//			$query->where("noc", "LIKE", "%".$keyword."%");
//		}
//		return $query;
//        // }
//
//	}
    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

	public function referrals()
	{
		return $this->belongsToMany(Referral::class)->withPivot('category');
	}

	public function clientaps()
    {
        return $this->belongsToMany(Clientap::class);
    }

	public function updatedBy()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

    public function currentNoc()
	{
		return $this->belongsTo(CurrentNoc::class, 'current_noc');
	}

    public function previousNoc()
	{
		return $this->belongsTo(PrevNoc::class ,'prev_noc');
	}
}
