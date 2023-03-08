<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;
use Session;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|string',
            'password_confirmation' => 'required|string|same:password',
            'phoneNumber' => 'required|integer'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = new User();
        $user->username = $request->username;
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

    public function login(Request $request){
        $json = $request->getContent();
        $data = json_decode($json);
        if($data){
            //intentar hacer una autentificacion con los parametro name y password 
            if(!Auth::attempt($request->only('email', 'password')))             
            {    
                //que recibimos en la request. Si no funciona tienes un mensaje de 401(no autorizados).                                                          
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            //si no existe o no coincide con los datos guardados...
            $user = User::where('email', 'like', $data->email)-> firstOrFail();

            if(Hash::check($data->password,$user->password )){
                $user->tokens()->delete();
                $token = $user->createToken('auth_token', [$user->type])->plainTextToken;

                return response()->json([
                'message' => 'Hi ', $user->email,
                'accessToken' => $token,
                'token_type' => 'bearer',
                'user' =>$user,
                ]);
            }else{
                //esa no es tu pass
                return response()->json([
                'message' => 'Aprende a escribir tu contraseña cruck'
            ]);
            }
        }
    }

    public function recoverPassword(Request $request){

        $json = $request->getContent();
        $data = json_decode($json);

        $email = $data->email;

        $user = User::where('email', $email)->first();
        try {
            if ($user) {

                //Generamos nueva contraseña aleatoria
                $newPassword = Str::random(10);
                $user->password = Hash::make($newPassword);
                $user->save();
                Mail::to($user->email)->send(new NotifyMail($newPassword));
                $response['status'] = 1;
                $response['msg'] = "Se ha enviado su nueva contraseña. Por favor, revise su correo.";
            } else {
                $response['status'] = 2;
                $response['msg'] = "Usuario no encontrado";
            }
        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['msg'] = "Se ha producido un error: " . $e->getMessage();
        }

        return response()->json($response);
    }
}
