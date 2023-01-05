<?php

namespace App\Http\Controllers;

use App\Models\Speciality;
use App\Models\Experience;
use App\Http\Requests\StoreSpecialityRequest;
use App\Http\Requests\UpdateSpecialityRequest;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class SpecialityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $specialities = Speciality::all();

        return response()->json([
            'status' =>  'success',
            'message' => 'Logged out successfully',
            'data' => [
                'specialities' => $specialities
            ]
        ], Response::HTTP_OK);
    }

    public function search(Request $request)
    {
        $name = $request->name;

        if(Speciality::where('name', 'LIKE', "%{$name}%")->exists())
        {
            $specialities = Speciality::where('name', 'LIKE', "%{$name}%")->get();

            return response()->json([
                'status' =>  'success',
                'message' => 'Searched successfully',
                'data' => [
                    'Specialities' => $specialities
                ]
            ], Response::HTTP_OK);
        }

        else{
            return response()->json([
                'status' =>  'failed',
                'message' => 'Not found',
                'data' =>  (object) []
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        if(Speciality::where('id', $id)->exists())
        {
            $experts_speciality = Speciality::find($id)->users;

            if(sizeof($experts_speciality) == 0)
            {
                return response()->json([
                    'status' =>  'success',
                    'message' => 'There are no experts of this speciality',
                    'data' => (object) []
                ], Response::HTTP_OK);
            }
            else
            {
                return response()->json([
                    'status' =>  'success',
                    'message' => 'Experts from this field have been found',
                    'data' => [
                        'experts_speciality' => $experts_speciality
                    ]
                ], Response::HTTP_OK);
            }

        }


            return response()->json([
                'status' =>  'failed',
                'message' => 'Not found',
                'data' => (object) []
            ], Response::HTTP_BAD_REQUEST);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSpecialityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSpecialityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Speciality  $speciality
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSpecialityRequest  $request
     * @param  \App\Models\Speciality  $speciality
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSpecialityRequest $request, Speciality $speciality)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Speciality  $speciality
     * @return \Illuminate\Http\Response
     */
    public function destroy(Speciality $speciality)
    {
        //
    }
}
