<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\User;
use App\Models\Wallet;
use App\Models\UserDay;
use App\Models\Experience;
// use App\Models\UserSpeciality;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm' => 'required|same:password',
            'role_id' => 'required',
        ]);

        $wallet = new Wallet();
        $wallet->quantity = 0;
        $wallet->save();


        $user = new User();
        $user->firstName = $request->firstName;
        $user->lastName = $request->lastName;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_id = $request->role_id;
        $user->wallet_id = $wallet->id;

        if ($request->role_id == Role::where('name', 'Expert')->first()->id)
        {
            $validateData = $request->validate([
                // 'image' => 'required|image|mimes:jpeg,bmp,png,jpg|max:3000',
                'number' => 'required',
                'address' => 'required',
                "days" => 'required|array',
                'days.*' => 'required_array_keys:day_id,startTime,endTime',
                'specialities' => 'required|array',
                'specialities.*' => "required_array_keys:speciality_id,details"
            ]);

            $path = null;

            if($request->hasFile('image'))
                $path = '/storage/'.$request->file('image')->store('images',['disk' => 'public']);

            $user->number = $request->number;
            $user->address = $request->address;
            $user->image = $path;
            $user->save();

            foreach($request->days as $day)
            {
                $user_day= new UserDay();
                $user_day->user_id = $user->id;
                $user_day->day_id = $day['day_id'];
                $user_day->startTime = $day['startTime'];
                $user_day->endTime = $day['endTime'];
                $user_day->save();
            }

            foreach($request->specialities as $speciality)
            {
                $experience = new Experience();
                $experience->user_id = $user->id;
                $experience->speciality_id = $speciality['speciality_id'];
                $experience->details = $speciality['details'];
                $experience->save();
            }


            return response()->json([
                'status' =>  'success',
                'message' => 'Registered successfully',
                'data' => ['Expert' => $user, 'token'=> $user->createToken('auth_token')->accessToken ]

            ], Response::HTTP_CREATED);
        }

        if ($request->role_id == Role::where('name', 'Normal')->first()->id) {

            $user->save();

            return response()->json([
                'status' =>  'success',
                'message' => 'Registered successfully',
                'data' => ['Normal' => $user, 'token'=> $user->createToken('auth_token')->accessToken]

            ], Response::HTTP_CREATED);
        }

        return response()->json([
            'status' =>  'failed',
            'message' => 'Invalid request',
            'data' => (object) []

        ], Response::HTTP_BAD_REQUEST);
    }

    public function login(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);


        if (auth()->attempt($validateData)) {

            $token = auth()->user()->createToken('auth_token')->accessToken;


            return response()->json([
                'status' => 'success',
                'message' => 'Logged in Successfully',
                'data' => [
                    'token' => $token
                ]
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid credentials',
                'data' => (object) []
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token()->revoke();

        return response()->json([
            'status' =>  'success',
            'message' => 'Logged out successfully',
            'data' => (object) []

        ], Response::HTTP_OK);
    }


}
