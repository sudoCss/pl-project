<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Favourite;
use App\Models\Wallet;
use App\Models\Rating;
use App\Models\UserDay;
use App\Models\Appointment;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $experts =  User::query()->where('role_id', Role::where('name', 'Expert')->first()->id)
        ->select('users.*')
        ->addSelect([
            'average_rate' =>
            Rating::query()
            ->select(DB::raw('AVG(ratings.stars)'))
            ->whereColumn('users.id', 'ratings.rated')
        ])
        ->addSelect([
            'favourited' =>
            Favourite::query()
            ->select(DB::raw("1"))->where('favourites.user_id', auth()->id())
            ->whereColumn('users.id', 'favourites.expert')
        ])
        ->get();

        return response()->json([
            'status' =>  'success',
            'message' => 'Get all experts successfully',
            'data' => [
                'experts' => $experts
            ]
        ], Response::HTTP_OK);
    }

    public function search(Request $request)
    {
        $name = $request->name;

        if(User::where(['role_id' =>  Role::where('name', 'Expert')->first()->id])->where('firstName', 'LIKE', "%{$name}%")->orwhere('lastName', 'LIKE', "%{$name}%")->exists())
        {
            $experts = User::where('firstName', 'LIKE', "%{$name}%")->orwhere('lastName', 'LIKE', "%{$name}%")->get();

            return response()->json([
                'status' =>  'success',
                'message' => 'Searched successfully',
                'data' => [
                    'Experts' => $experts
                ]
            ], Response::HTTP_OK);
        }

        else{
            return response()->json([
                'status' =>  'failed',
                'message' => 'Expert not found',
                'data' =>  (object) []
            ], Response::HTTP_OK);
        }
    }

    public function show($id)
    {
        if(User::where(['id' => $id])->exists())
        {
            $user = User::find($id);

            if($user->role_id == Role::where('name', 'Expert')->first()->id)
            {
                $expert = $user;

                return response()->json([
                    'status' =>  'success',
                    'message' => 'Expert has found',
                    'data' => $expert
                ], Response::HTTP_OK);
            }
        }

            return response()->json([
                'status' =>  'failed',
                'message' => 'Not found',
                'data' => (object) []
            ], Response::HTTP_OK);

    }

    public function charge(Request $request)
    {
        if(!User::where(['role_id'=> Role::where('name', 'Admin')->first()->id, 'id' => auth()->user()->id])->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => 'You are not Admin',
                'data' => (object) []
            ], Response::HTTP_NOT_FOUND);
        }

        $validateData = $request->validate([
            'email' => 'required|email',
            'quantity' => 'required|integer|min:1'
        ]);

        if(!User::where('email', $request->email)->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => 'User Not found',
                'data' => (object) []
            ], Response::HTTP_NOT_FOUND);
        }

        if(User::where(['email' => $request->email, 'role_id' => Role::where('name', 'Admin')->first()->id])->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => 'Admin have no permission',
                'data' => (object) []
            ], Response::HTTP_NOT_FOUND);
        }


        $wallet = Wallet::find(User::where('email', $request->email)->first()->wallet_id);
        $wallet->quantity += $request->quantity;
        $wallet->save();

            return response()->json([
                'status' =>  'success',
                'message' => 'Wallet has been charge successfully',
                'data' => [
                    'Wallet' => $wallet->quantity
                ]
            ], Response::HTTP_OK);
    }

    public function availableTimes($id)
    {
        $results = array();
        $expertDays = UserDay::where('user_id', $id)->orderBy('day_id', 'ASC')->get();
        foreach($expertDays as $day)
        {
            $hours = array();
            for($i = 0; $i < 24; $i++)
            {
                $hours[$i] = $i >= $day->startTime && $i < $day->endTime;
            }
            $expertSchedule = Appointment::where(['expert'=> $id, 'day_id' => $day->day_id])
                ->orderBy('startTime', 'ASC')
                ->get();
            foreach($expertSchedule as $appointment)
            {
                for($i = $appointment->startTime; $i < $appointment->endTime; $i++)
                {
                    $hours[$i] = false;
                }
            }

            for($i = 0; $i < 24; $i++)
            {
                if($hours[$i])
                {
                    for($j = $i; $j < 24; $j++)
                    {
                        if(!$hours[$j])
                        {
                            $results[] = [
                                'day' => $day->day_id,
                                'start' => $i,
                                'end' => $j,
                            ];
                            break;
                        }
                    }
                }
            }
        }

        return response()->json([
            'status' =>  'success',
            'message' => 'The times available to the expert',
            'data' => ['times' => $results]
        ], Response::HTTP_OK);
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
