<?php

namespace App\Http\Controllers;

use App\ClientOutcome;
use App\Outcome;
use Illuminate\Http\Request;

class OutcomeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(
            [
                'status' => 'required|string',
            ],
            [
                'outcome_id' => 'required|string',
            ],
            [
                'category_id' => 'required|string',
            ],
            [
                'client_id' => 'required|string',
            ]

        );

        $outcome = new ClientOutcome();
        $outcome->outcome_id = $request->outcome_id;
        $outcome->status = $request->status;
        $outcome->category_id = $request->category_id;
        $outcome->client_id = $request->client_id;
        $outcome->save();

        return $outcome;
    }

    public function show($id)
    {

        $outcome = Outcome::where('category_id', $id)->get();

        return $outcome;
    }

    public function getClientOutcome($id, $category_id)
    {

        $outcomes = ClientOutcome::with('outcome')->where('client_id',$id)
            ->where('category_id',$category_id)->get();

        return $outcomes;
    }
}
