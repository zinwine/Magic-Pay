<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->ip = $request->ip();
        $user->user_agent = $request->server('HTTP_USER_AGENT');
        $user->login_at = date('Y-m-d H:i:s');
        $user->save();

        Wallet::firstOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'account_number' => UUIDGenerator::accountNumber(),
                'amount' => 0
            ]
        );

        $token = $user->createToken('Magic Pay')->accessToken;
        return success('Successfully registered', ['token' => $token]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate(
            [
            'phone' => ['required', 'string'],
            'password' => ['required', 'string']
            ]
        );
        if(Auth::attempt(['phone' => $request->phone, 'password' => $request->password])){
            $user = auth()->user();
            $user->ip = $request->ip();
            $user->user_agent = $request->server('HTTP_USER_AGENT');
            $user->login_at = date('Y-m-d H:i:s');
            $user->update();

            Wallet::firstOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'account_number' => UUIDGenerator::accountNumber(),
                    'amount' => 0
                ]
            );

            $token = $user->createToken('Magic Pay')->accessToken;
            return success('Successfully login', ['token' => $token]);

        }
        return fail('Login failed', null);
    }

    public function logout(){
        $user = auth()->user();
        $user->token()->revoke();
        return success('Successfully Logout', null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
