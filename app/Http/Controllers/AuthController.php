<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function register(Request $request) {

        $data = $request->validate([
            'name' => 'required||string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
            'tel' => 'required',
            // 'avatar' => 'required|image|mimes:jpg,png,jpeg|max:2048|dimensions:min_width=400,min_height=400,max_width=500,max_height=500',
            'role' => 'required|integer',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'tel' => $data['tel'],
             'avatar' => 'https://www.google.co.th/url?sa=i&url=https%3A%2F%2Fwww.sermpisit.com%2Fprofile%2F&psig=AOvVaw3AXNsye8CwOeYEW6RowVpf&ust=1641877791054000&source=images&cd=vfe&ved=0CAsQjRxqFwoTCJDo68a1pvUCFQAAAAAdAAAAABAD',
            'role' => $data['role'],
        ]);

        $token = $user->createToken('my-device')->plainTextToken;

        $reponse = [
            'user' => $user,
            'token' => $token
        ];

        return response($reponse,201);

    }



    public function login(Request $request) {

        $data = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        //check email in db
        $email_password = User::where('email', $data['email'])->first();

        
        if(!$email_password || !Hash::check($data['password'], $email_password->password)) {

            $response = [
                'massage' => 'not email ro password'
            ];

            return response($response);
        }else{

            $token = $email_password->createToken('my-device')->plainTextToken;
    
            $reponse = [
                'user' => $email_password,
                'token' => $token
            ];
    
            return response($reponse,201);
            
        }


    }
}
