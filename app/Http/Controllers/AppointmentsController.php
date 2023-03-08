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
use Illuminate\Support\Facades\DB;

class AppointmentsController extends Controller
{
    public function registerAppointments(Request $request){

        $validator = Validator::make(
            json_decode($request->getContent(), true),
            [
                "service" => ["required"],
                "date" => ["required"],
                "time" => ["required"]
            ]
        );

        if ($validator->fails()){
            $response['status'] = 0;
            $response['msg'] = "Data fail in validator";
        } else {
            //Generar la cita
            $data = $request->getContent();
            $data = json_decode($data);

            try {

                $user = auth()->user();
                $appointment = new Appointment();
                $appointment->user_id = $user->id;
                $appointment->service_id = $data->service;
                $appointment->date = $data->date;
                $appointment->time = $data->time;

                $appointment->save();

                $response['status'] = 1;
                $response['msg'] = "Cita guardada";
            } catch (\Exception $e) {
                $response['status'] = 0;
                $response['msg'] = "Error was found: " . $e->getMessage();
            }
        }
        return response()->json($response);
    }

    public function deleteAppointment(Request $request){

        $validator = Validator::make(json_decode($request->getContent(), true),
            [
                "id" => ["required"]
            ]
        );

        if ($validator->fails()){
            $response['status'] = 0;
            $response['msg'] = "Data fail in validator";
        } else {
            $data = $request->getContent();
            $data = json_decode($data);

            try {

                $user = auth()->user();
                $appointment = Appointment::find($data->id);

                if($appointment->user_id == $user->id){

                    $appointment->delete();

                    $response['status'] = 1;
                    $response['msg'] = "Appointment deleted";
                }else{
                    $response['status'] = 0;
                    $response['msg'] = "Appoinment not found " ;
                }
                
            } catch (\Exception $e) {
                $response['status'] = 0;
                $response['msg'] = "Error was found: " . $e->getMessage();
            }
        }
        return response()->json($response);
    }
}
