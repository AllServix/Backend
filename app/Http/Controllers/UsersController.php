<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|string',
            'password_confirmation' => 'required|string|same:password',
            'phoneNumber' => 'required|integer'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = new User();
        $user->name = $request->name;
        $user->lastName = $request->lastName;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->password_confirmation = Hash::make($request->password_confirmation);
        $user->phoneNumber = $request->phoneNumber;

        $user->save();

        return response()->json([
            "status" => 1, 
            "message" => 'Successfull registration'
        ]);

    }
}
