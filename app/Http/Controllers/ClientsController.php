<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Client;
use App\User;
use App\Activity;
use App\Clientap;
use App\Clienteducation;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use \App\Rules\PhoneNumber;
use \App\Rules\PostalCode;

class ClientsController extends Controller
{

    public function index(Request $request)
    {
        $clients = Client::with('user')->orderBy('id', 'desc')->paginate($request->pageSize);
        return $clients;
    }

    public function search(Request $request)
    {
        $search =  $request->input('string');

        // $client = new Mi_Client('http://127.0.0.1:7700', 'q7QHwGiX841a509c8b05ef29e55f2d94c02c00635f729ccf097a734cbdf7961530f47c47');
        // $index = $client->index($search )->addDocuments($resultClients);
        $resultClients = Client::search($search)
        ->with('user')
        ->paginate($request->pageSize);

        return $resultClients;
    }

    public function searchRegistration(Request $request)
    {
        return Client::whereDoesntHave('groupActivities', function (Builder $query) use ($request) {
            $query->where('groupActivity_id', $request->groupActivity_id);
        })
            ->search($request->input('string'))
            ->with('user')->take(5)->get();
    }

    public function store(Request $request)
    {

        $client = Client::create($request->all());
        $activity=new Activity();
        $activity->description=$client->user->username." Client Added";
        $activity->user_id=Auth::id();
        $activity->save();
        return $client;
    }

    public function show($id)
    {
        $client = Client::with(['user','educations','highestEducation','employments','goals','meetings','trainings','workshops','outcomes'])->findorFail($id);

        return $client;
    }


    public function update(Request $request, $id)
    {
        $phoneNumber = new PhoneNumber;

        $client = Client::with('user')->findorFail($id);

        $request->validate([
            'user.firstName' => 'bail|required|string|max:255',
            'user.lastName' => 'required|string|max:255',
            'user.username' => 'required|string|max:30|min:5|unique:users,username,' . $client->user->id,
            'wc_id' => 'required|unique:clients,wc_id,' . $id,
            'phonePrimary' => ['nullable', $phoneNumber],
            'phoneSecondary' => ['nullable', $phoneNumber],
            'postalCode' => ['nullable', new PostalCode],

        ]);

        $dob = new Carbon($request->dob, 'America/Toronto');
        $doa = new Carbon($request->doa, 'America/Toronto');

        $client->user->update([
            'firstName' => $request->user['firstName'],
            'lastName' => $request->user['lastName'],
            'username' => $request->user['username'],
            'email' => $request->user['email'],
        ]);

        $client->update([
            'wc_id' => $request->wc_id,
            'immigrationStatus' => $request->immigrationStatus,
            'dob' => $dob,
            'doa' => $doa,
            'gender' => $request->gender,
            'streetAddress' => $request->streetAddress,
            'city' => $request->city,
            'province' => $request->province,
            'postalCode' => $request->postalCode,
            'phonePrimary' => $phoneNumber->formatPhoneNumber($request->phonePrimary),
            'phoneSecondary' => $phoneNumber->formatPhoneNumber($request->phoneSecondary),
            'motherTongue' => $request->motherTongue,
            'countryOrigin' => $request->countryOrigin,
            'engProficiency' => $request->engProficiency,
            'interpreterNeeded' => $request->interpreterNeeded,
            'interpreterLanguage' => $request->interpreterLanguage,
            'childcareNeeded' => $request->childcareNeeded,
            'notes' => $request->notes,
        ]);
        $activity=new Activity();
        $activity->description=$request->user['username']." | Client Updated";
        $activity->user_id=Auth::id();
        $activity->save();
        return $client;
    }

    public function postHighestEducaiton(Request $request)
    {
        $client = Client::findorFail($request->client_id);

        $client->highestEducation_id = $request->highestEducation_id;

        $client->save();
        $activity=new Activity();
        $activity->description=$client->user->username." | Client Education Added";
        $activity->user_id=Auth::id();
        $activity->save();
    }

    public function updateOutcomes(Request $request)
    {
        $client = Client::findOrFail($request->client_id);

        // foreach ($request->outcomeSelected as $outcome) {
        //   $client->outcomes()->sync([$outcome => ['created_at' => new Carbon]]);
        // }

        $client->outcomes()->syncWithoutDetaching([$request->outcomeSelected => ['created_at' => new Carbon]]);
        $activity=new Activity();
        $activity->description=$client->user->username." | Client Outcome Updated";
        $activity->user_id=Auth::id();
        $activity->save();

        return $client;
    }

    public function getActionPlans($id)
    {
        $client = Client::find($id);

        $clientaps = $client->clientaps()->get();
        foreach ($clientaps as $actionPlan) {
            $actionPlan['noc'] = unserialize($actionPlan['noc']);
        }

        return $clientaps;
    }

    public function destroy($id)
    {
        $client = Client::findorFail($id);
        $client->delete();
        $activity=new Activity();
        $activity->description=$client->user->username." | Client Deleted";
        $activity->user_id=Auth::id();
        $activity->save();

        return 204;
    }

   public function reportReg(Request $request)
   {
       if ($request->status == 'all')
           return Client::whereBetween('created_at', [$request->startDate, $request->endDate])->get();
       return Client::where('immigrationStatus', $request->status)->whereBetween('created_at', [$request->startDate, $request->endDate])->get();
   }


   public function reportRegChart(Request $request)
   {

       $total = Client::whereBetween('created_at', [$request->startDate, $request->endDate])->count();
       $Citizen = Client::where('immigrationStatus', 'Citizen')->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
       $Immigrant = Client::where('immigrationStatus', 'Immigrant')->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
       $Refugee = Client::where('immigrationStatus', 'Refugee')->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
       $labels = ["Refugee", "Citizen", "Immigrant"];
       $series = [];
       $series[0] = array($Refugee, $Citizen, $Immigrant);
       $response = array('Chart' => "ClientReg", 'labels' => $labels, 'series' => $series);
       return $response;
   }

//    public function getDemographicReport(Request $request)
//    {
//        $request->validate([
//            'start_date' => 'date|nullable|required_with:end_date',
//            'end_date' => 'date|nullable|required_with:start_date',
//        ]);
//
//        //geographic location (city)
//        $geographic = Client::when($request->start_date, function ($query) use ($request) {
//            return $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
//        })->select(\DB::raw("CONCAT(`city`,', ',`province`) AS geographic"), \DB::raw("count(*) AS total"))->groupBy('geographic')->get();
//
//
//        //age range
//        $age = Client::when($request->start_date, function ($query) use ($request) {
//            return $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
//        })->select(\DB::raw("TIMESTAMPDIFF(YEAR, DATE(dob), current_date) AS age"), \DB::raw("count(*) AS total"))->groupBy('age')->get();
//
//        //gender
//        $gender = Client::when($request->start_date, function ($query) use ($request) {
//            return $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
//        })->select('gender', \DB::raw('count(*) as total'))->groupBy('gender')->get();
//
//        //immigration status
//        $immigrationStatus = Client::when($request->start_date, function ($query) use ($request) {
//            return $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
//        })->select('immigrationStatus', \DB::raw('count(*) as total'))->groupBy('immigrationStatus')->get();
//
//        //country of origin
//        $countryOrigin = Client::when($request->start_date, function ($query) use ($request) {
//            return $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
//        })->select('countryOrigin', \DB::raw('count(*) as total'))->groupBy('countryOrigin')->get();
//
//        //clients mother tounge language
//        $motherTongue = Client::when($request->start_date, function ($query) use ($request) {
//            return $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
//        })->select('motherTongue', \DB::raw('count(*) as total'))->groupBy('motherTongue')->get();
//
//        //highestlevel of education  //client education
//        $educations = Clienteducation::when($request->start_date, function ($query) use ($request) {
//            return $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
//        })->select('education_level', \DB::raw('count(*) as total'))->groupBy('education_level')->get();
//
//        //noc Top 10 National Occupational Classification (NOC) for the Month:
//        //clientaps do the sortby here because it requires the limit of 10
//        $noc = Clientap::when($request->start_date, function ($query) use ($request) {
//            return $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
//        })->select('noc', \DB::raw('count(*) as total'))->groupBy('noc')->get()->sortByDesc('total')->values()->take(10);
//
//
//        foreach ($noc as $key => $value) {
//            $temp = unserialize($value->noc);
//            $noc[$key] = [
//                'code' => $temp['code'],
//                'title' => $temp['title'],
//                'total' => $value->total
//            ];
//        }
//
//        $response = [
//            'data' => [
//                'geographic' => [
//                    'body' => $geographic,
//                    'title' => 'Geographic Location',
//                    'rowTitles' => ['City', 'Clients'],
//                ],
//                'age' => [
//                    'body' => $age,
//                    'title' => 'Age Range',
//                    'rowTitles' => ['Age', 'Clients'],
//                ],
//                'gender' => [
//                    'body' => $gender,
//                    'title' => 'Gender',
//                    'rowTitles' => ['Gender', 'Clients'],
//                ],
//                'immigrationStatus' => [
//                    'body' => $immigrationStatus,
//                    'title' => 'Immigration Status',
//                    'rowTitles' => ['Immigration Status', 'Clients'],
//                ],
//                'countryOrigin' => [
//                    'body' => $countryOrigin,
//                    'title' => 'Country Of Origin',
//                    'rowTitles' => ['Country Of Origin', 'Clients'],
//                ],
//                'motherTongue' => [
//                    'body' => $motherTongue,
//                    'title' => 'Client’s Mother Tongue Language',
//                    'rowTitles' => ['Language', 'Clients'],
//                ],
//                'education_level' => [
//                    'body' => $educations,
//                    'title' => 'Educations',
//                    'rowTitles' => ['Educations', 'Clients'],
//                ],
//                'noc' => [
//                    'body' => $noc,
//                    'title' => 'Top 10 National Occupational Classification (NOC)',
//                    'rowTitles' => ['NOC', 'NOC Description', 'Clients'],
//                ]
//            ],
//            'start' => $request->start_date,
//            'end' => $request->end_date,
//        ];
//
//        // the purpose of this loop is to change the values of null to Unknown to make it more readable
//        // also sorts arrays by the name of thekey in the arryas
//        // sums the total of each
//        foreach ($response['data'] as $key => $value) {
//            $response['data'][$key]['total'] = $value['body']->sum('total');
//            if ($key == 'noc') {
//                // the nocs in clientap will not have a null value
//                continue;
//            }
//            foreach ($response['data'][$key]['body'] as $key2 => $value2) {
//                if ($response['data'][$key]['body'][$key2][$key] == null && !is_int($response['data'][$key]['body'][$key2][$key])) {
//                    // unsetting the null values so they can be sorted and placed at the bottom of the table no matter what
//                    unset($response['data'][$key]['body'][$key2]);
//                    $response['data'][$key]['unknown'] = $value2['total'];
//                    continue;
//                }
//            }
//
//            $response['data'][$key]['body'] = $value['body']->sortBy($key)->values();
//        }
//
//        return response($response, 200);
//    }
}
