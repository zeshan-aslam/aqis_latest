<?php

namespace App\Http\Controllers;

use App\ClientQuestion;
use Illuminate\Http\Request;

class ClientQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ClientQuestion::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $clientQuestion = new ClientQuestion();
        $clientQuestion->title = $request->title;
        $clientQuestion->description = $request->description;
        $clientQuestion->client_id = $request->client_id;
        $clientQuestion->feedback_id = $request->feedback_id;
        $clientQuestion->save();
        return $clientQuestion;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ClientQuestion  $clientQuestion
     * @return \Illuminate\Http\Response
     */
    public function show(ClientQuestion $clientQuestion)
    {
    }
    public function  getClientQuestions($clientmeeting_id, $client_id)
    {

        return ClientQuestion::with('feedback')->where(['client_id' => $client_id, 'clientmeeting_id' => $clientmeeting_id])->get();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ClientQuestion  $clientQuestion
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientQuestion $clientQuestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClientQuestion  $clientQuestion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientQuestion $clientQuestion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClientQuestion  $clientQuestion
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientQuestion $clientQuestion)
    {
        //
    }
}
