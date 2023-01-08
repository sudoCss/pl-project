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
use App\Models\Experience;
use App\Models\Speciality;

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
        // ->with('specialities')
        ->addSelect([
            'average_rate' =>
            Rating::query()
            ->select(DB::raw('AVG(ratings.stars)'))
            ->whereColumn('users.id', 'ratings.rated')
        ])
        ->addSelect([
            'favourited' =>
            Favourite::query()
            ->select(DB::raw("1"))
            ->where('favourites.user_id', auth()->id())
            ->whereColumn('users.id', 'favourites.expert')
        ])
        ->addSelect([
            'favourite_id' =>
            Favourite::query()
            ->select(DB::raw('favourites.id'))
            ->where('favourites.user_id', auth()->id())
            ->whereColumn('users.id', 'favourites.expert')
        ])
        ->addSelect([
            'speciality_id' =>
            Experience::query()
            ->select(DB::raw('speciality_id'))
            ->whereColumn('users.id', 'experiences.user_id')
        ])
        ->addSelect([
            'details' =>
            Experience::query()
            ->select(DB::raw('details'))
            ->whereColumn('users.id', 'experiences.user_id')
        ])
        ->get();

        // ->addSelect([
        //     'speciality' =>
        //     Speciality::query()
        //     ->select(DB::raw('specialities.name'))
        //     // ->with('specialities')
        //     ->first()
            // ->where('specialities.id', 'experiences.speciality_id')
            // ->where('users.id', 'experiences.user_id')
        // ])

        //     'speciality Name' =>
        //     Experience::query()
        //     ->select(DB::raw('speciality.name'))
        //     ->where('specialities.id', Experience::query()->select(DB::raw('speciality_id'))->whereColumn('users.id', 'experiences.user_id'))
        // ])

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
            $experts = User::where('firstName', 'LIKE', "%{$name}%")->orwhere('lastName', 'LIKE', "%{$name}%")
            ->addSelect([
                'average_rate' =>
                Rating::query()
                ->select(DB::raw('AVG(ratings.stars)'))
                ->whereColumn('users.id', 'ratings.rated')
            ])
            ->addSelect([
                'favourited' =>
                Favourite::query()
                ->select(DB::raw("1"))
                ->where('favourites.user_id', auth()->id())
                ->whereColumn('users.id', 'favourites.expert')
            ])
            ->addSelect([
                'favourite_id' =>
                Favourite::query()
                ->select(DB::raw('favourites.id'))
                ->where('favourites.user_id', auth()->id())
                ->whereColumn('users.id', 'favourites.expert')
            ])
            ->addSelect([
                'speciality_id' =>
                Experience::query()
                ->select(DB::raw('speciality_id'))
                ->whereColumn('users.id', 'experiences.user_id')
            ])
            ->addSelect([
                'details' =>
                Experience::query()
                ->select(DB::raw('details'))
                ->whereColumn('users.id', 'experiences.user_id')
            ])
            ->get();

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
            ], Response::HTTP_BAD_REQUEST);
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
            ], Response::HTTP_BAD_REQUEST);

    }

    public function charge(Request $request)
    {
        if(!User::where(['role_id'=> Role::where('name', 'Admin')->first()->id, 'id' => auth()->user()->id])->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => 'You are not Admin',
                'data' => (object) []
            ], Response::HTTP_BAD_REQUEST);
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
            ], Response::HTTP_BAD_REQUEST);
        }

        if(User::where(['email' => $request->email, 'role_id' => Role::where('name', 'Admin')->first()->id])->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => 'Admin have no permission',
                'data' => (object) []
            ], Response::HTTP_BAD_REQUEST);
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
                $hours[$i] = ($i >= $day->startTime) && ($i < $day->endTime);
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
                            $i = $j;
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

    public function update(Request $request)
    {
        $validateData = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email|unique:users,id,'.auth()->user()->id,
            'number' => 'required',
            'address' => 'required',
            // 'image' => 'image|mimes:jpeg,bmp,png,jpg|max:3000',
        ]);

        $path = auth()->user()->image;

        if($request->hasFile('image'))
            $path = '/storage/'.$request->file('image')->store('images',['disk' => 'public']);

        $user = auth()->user();
        $user->firstName = $request->firstName;
        $user->lastName = $request->lastName;
        $user->email = $request->email;
        $user->number = $request->number;
        $user->address = $request->address;
        $user->image = $path;
        $user->save();

        return response()->json([
            'status' =>  'success',
            'message' => 'Updated successfully',
            'data' => ['User' => $user]

        ], Response::HTTP_OK);
    }

    public function changePassword(Request $request)
    {
        $validateData = $request->validate([
            'password' => 'required',
            'confirm' => 'required|same:password',
        ]);

        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'status' =>  'success',
            'message' => 'Updated successfully',
            'data' => ['User' => $user]

        ], Response::HTTP_OK);
    }

    public function profile()
    {
        $userData = auth()->user();

        return response()->json([
            'status' => 'success',
            'message' => 'User data',
            'data' => ['UserData' => $userData]
        ]);
    }

    public function updateExpert(Request $request)
    {
        if(auth()->user()->role_id != Role::where('name', 'Expert')->first()->id)
        {
            return response()->json([
                'status' => 'failed',
                'message' => 'You are not expert',
                'data' => (object) []
            ]);

        }

        $validateData = $request->validate([
            "days" => 'required|array',
            'days.*' => 'required_array_keys:day_id,startTime,endTime',
            'specialities' => 'required|array',
            'specialities.*' => "required_array_keys:speciality_id,details"
        ]);

        UserDay::where('user_id', auth()->id())->delete();
        foreach($request->days as $day)
        {
            $user_day= new UserDay();
            $user_day->user_id = auth()->id();
            $user_day->day_id = $day['day_id'];
            $user_day->startTime = $day['startTime'];
            $user_day->endTime = $day['endTime'];
            $user_day->save();
        }

        Experience::where('user_id', auth()->id())->delete();
        foreach($request->specialities as $speciality)
        {
            $experience = new Experience();
            $experience->user_id = auth()->id();
            $experience->speciality_id = $speciality['speciality_id'];
            $experience->details = $speciality['details'];
            $experience->save();
        }

        $user = auth()->user();

        return response()->json([
            'status' =>  'success',
            'message' => 'Registered successfully',
            'data' => ['Expert' => $user ]

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
