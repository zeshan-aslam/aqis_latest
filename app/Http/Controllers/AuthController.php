<?php

namespace App\Http\Controllers;

use App\User;
use App\Facilitator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Mail\NewFacilitatorMail;
use App\Mail\RegisterEmail;
use \App\Rules\PhoneNumber;
use \App\Rules\PostalCode;
use Illuminate\Foundation\Auth\RegistersUsers;
// use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validatedData = $request->validate(
            [
            'username' => 'bail|required',
            'password' => 'required',
            ]
        );
        $authRequest = new \GuzzleHttp\Client;

        $loginEndpoint = config('passportconfig.loginEndpoint');
        $loginClientID = config('passportconfig.id');
        $loginSecret = config('passportconfig.secret');

        $active = User::where('username', $request->username)->where('active', 1)->exists();

        if (!$active) {
            return response()->json('This user is not active or doesn\'t exist', 403);
        } else {
            try {
                $response = $authRequest->post(
                    $loginEndpoint, [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => $loginClientID,
                        'client_secret' => $loginSecret,
                        'username' => $request->username,
                        'password' => $request->password,
                    ]
                ]);

                return $response->getBody();
            } catch (\GuzzleHttp\Exception\BadResponseException $error) {
                    if ($error->getCode() === 400) {
                        return response()->json('Invalid Request. Please enter a username or password', $error->getCode());
                    } else if ($error->getCode() === 401) {
                        return response()->json('Your credentials are incorrect. Please try again.', $error->getCode());
                    }

                    return response()->json('Something went wrong on the server.', $error->getCode());
            }
        }
    }

    public function refreshToken(Request $request)
    {
        $request->validate(
        [
            'refresh_token' => 'required',
        ]
        );
        $authRequest = new Client;

        $loginEndpoint = config('passportconfig.loginEndpoint');
        $loginClientID = config('passportconfig.id');
        $loginSecret = config('passportconfig.secret');
        try {

        $response = $authRequest->post(
            $loginEndpoint,
            [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'client_id' => $loginClientID,
                'client_secret' => $loginSecret,
                'refresh_token' => $request->refresh_token,
                'scope' => '',
            ]
            ]
        );

        return json_decode((string) $response->getBody(), true);
        } catch (\GuzzleHttp\Exception\BadResponseException $error) {

        return response()->json('Something went wrong on the server.', $error->getCode());
        }
    }

    public function register(Request $request)
    {

        $validatedData = $request->validate(
            [
            'firstName' => 'bail|required|string|max:255',
            'lastName' => 'required|string|max:255',
            'username' => 'unique:users,username|required|string|max:30|min:5|',
            'password' => 'required|string|min:6|',
            'wc_id' => 'unique:clients,wc_id|required|numeric|',
            ]
        );

        $user = User::create(
            [
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => 1,
            'token' =>  uniqid(),
            'active' => 1,
            ]
        );
        Mail::to($user->email)->send(new RegisterEmail($user));
        $client = $user->client()->create(
            [
            'wc_id' => $request->wc_id,
            'phonePrimary' => $request->phonePrimary,
            ]
        );
        $user->client_id = $client->id;
        $user->save();


        return $client;
    }

    function registerStaff(Request $request)
    {
        $phoneNumber = new PhoneNumber;

        $request->validate(
            [
                'user.firstName' => 'bail|required|string|max:255',
                'user.lastName' => 'required|string|max:255',
                'user.username' => 'unique:users,username|required|string|max:30|min:5|',
                'user.email' => 'unique:users,email|required|string|email|max:255|',
                'user.password' => 'required|string|min:6|',
                'phonePrimary' => ['required',  $phoneNumber],
                'phoneEmerg' => ['nullable',  $phoneNumber],
                'position' => 'required|string|max:255',
                'postalCode' => ['nullable', new PostalCode],
                'phonePersonal' =>  ['nullable',  $phoneNumber],
                'contactEmerg' => 'nullable|string|max:255',
            ],
            [
                'user.firstName.required' => 'The first name field is required.',
                'user.lastName.required' => 'The last name field is required.',
                'user.username.required' => 'The username field is required.',
                'user.username.unique' => 'The username has already been taken.',
                'user.email.required' => 'The email field is required.',
                'user.email.unique' => 'The email has already been taken.',
                'user.password.required' => 'The password field is required.',
                'phonePrimary.required' => 'The primary phone field is required.',
            ]
        );

        $user = User::create(
            [
                'firstName' => $request->user['firstName'],
                'lastName' => $request->user['lastName'],
                'username' => $request->user['username'],
                'email' => $request->user['email'],
                'password' => Hash::make($request->user['password']),
                'role_id' => 2,
                'active' => 1,
            ]
        );

        $user->staff()->create(
            [
                'phonePrimary' =>  $phoneNumber->formatPhoneNumber($request->phonePrimary),
                'phoneEmerg' =>  $phoneNumber->formatPhoneNumber($request->phoneEmerg),
                'position' => $request->position,
                'gender' => $request->gender,
                'streetAddress' => $request->streetAddress,
                'city' => $request->city,
                'province' => $request->province,
                'postalCode' => $request->postalCode,
                'phonePersonal' =>  $phoneNumber->formatPhoneNumber($request->phonePersonal),
                'contactEmerg' => $request->contactEmerg,
            ]
        );
    }

    public function registerFacilitator(Request $request)
    {

        $request->validate(
            [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|',
            ]
        );

        $user = User::create(
            [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => 2,
            ]
        );

        Mail::to($user->email)
                ->queue(new NewFacilitatorMail($user));

        $facilitator = new Facilitator();
        $facilitator->firstName = $request->firstName;
        $facilitator->lastName =  $request->lastName;
        $facilitator->phonePrimary =  $request->phonePrimary;
        $facilitator->gender =  $request->gender;
        $facilitator->address =  $request->address;
        $facilitator->active =  1;
        $user->facilitator()->save($facilitator);

        $user->facilitator_id = $facilitator->id;
        $user->save();


        return $facilitator;

    }


    public function updatePassword(Request $request)
    {

        $request->validate(
            [
            'token' => 'required|string',
            'password' => 'required|string|min:6|',
            ]
        );

        $user = User::where('token',$request->token)->first();
     
        if($user){
            $user->update(
                [
                'password' => Hash::make($request->password),
                'token' => null,
                ]
            );
            return response()->json('User Updated Successfully', 200);
        }
        return response()->json('Link Expired', 400);


    }

    public function logout()
    {

        auth()->user()->tokens->each(

            function ($token, $key) {

                $token->revoke();

            }
        );

        return response()->json('Logged out successfully.', 200);

    }
}
