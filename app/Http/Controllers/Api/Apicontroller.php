<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user;
use Illuminate\Support\Facades\Hash;
// use Illuminate\support\facades\Auth;


class Apicontroller extends Controller
{
    //Register Api (Post, formdata)

    public function register(Request $request){

     
        

        //Data Validation

        $request->validate([
            'name' => "required",
            'email' => "required|email|unique:users",
            'password' => "required|confirmed",

        ]);

        // save data

        user::create([
            'name' =>$request->name,
            'email' =>$request->email,
            'password' =>Hash::make($request->password),
        ]);

        return response()->json([

            "status" => true,
            "message" => "user registered successfully"

        ]);




    }

    // login Api (Post , formdata)

    public function login(Request $request){

   // data validation 

   $request->validate([

    "email"=>"required|email",
    "password"=>"required"


   ]);
   $user = user::where("email",$request->email)->first();

   if(!empty($user)){
    if(Hash::check($request->password,$user->password)){

        $token = $user->createToken("myToken")->plainTextToken;

        return response()->json([
            "status"=>true,
            "message"=>"Login successful",
            "token" => $token
        ]);


    }
    return response()->json([
        "status" => false,
        "message" => "password didn't match",

    ]);
   }
    

    return response()->json([
        "status"=> false,
        "message"=> "Invalid login details"

    ]);
}

    // profile Api (Get)

    public function profile(){
        $data = auth()->user(); //Auth::user();

        return response()->json([

            "status" =>true,
            "message" => "profile data",
            "user" => $data

        ]);


    }

    // logout Api (Get)

    public function logout(){

        auth()->user()->tokens()->delete();

        return response()->json([

            "status" =>true,
            "message" => "user logged out"

        ]);


    }
}
