<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Role;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use Symfony\Component\HttpFoundation\Response;

use App\Models\User;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $rated = User::find($id);
        $ratings = $rated->rated();
        return $ratings;
        $ratings = Rating::where('rated', $id)->join('users','users.id','=','ratings.rater')->select('users.id', 'firstName', 'lastName', 'image', 'stars')->get();

        return response()->json([
            'status' =>  'success',
            'message' => 'Ratings has found',
            'data' => [
                'ratings' => $ratings
            ]
        ], Response::HTTP_OK);
    }

    public function store(StoreRatingRequest $request, $id)
    {
        if(!User::where('id', $id)->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => 'Expert not found',
                'data' => (object) []
            ], Response::HTTP_NOT_FOUND);
        }

        $validateData = $request->validate([
            'stars' => 'required|integer|min:1|max:5'
        ]);

        $rating = new Rating();
        $rating->stars = $request->stars;
        $rating->rater = auth()->user()->id;
        $rating->rated = $id;
        $rating->save();

        return response()->json([
            'status' =>  'success',
            'message' => 'Ratings has created',
            'data' => [
                'rating' => $rating
            ]
        ], Response::HTTP_OK);
    }

    public function update(UpdateRatingRequest $request, $id)
    {
        if(!Rating::where('id', $id)->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => 'Rating not found',
                'data' => (object) []
            ], Response::HTTP_NOT_FOUND);
        }

        $validateData = $request->validate([
            'stars' => 'required|integer|min:1|max:5'
        ]);

        $rating = Rating::where('id', $id)->first();
        $rating->stars = $request->stars;
        $rating->save();

        return response()->json([
            'status' =>  'success',
            'message' => 'Ratings has updated',
            'data' => [
                'rating' => $rating
            ]
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        if(!Rating::where('id', $id)->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => 'Rating not found',
                'data' => (object) []
            ], Response::HTTP_NOT_FOUND);
        }


        $rating = Rating::where('id', $id)->first();
        $rating->delete();

        return response()->json([
            'status' =>  'success',
            'message' => 'Rating has been deleted',
            'data' => (object) []
        ], Response::HTTP_OK);

    }

    public function average($id)
    {
        if(!User::where(['id' => $id, 'role_id' => Role::where('name', 'Expert')->first()->id])->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => 'Expert not found',
                'data' => (object) []
            ], Response::HTTP_NOT_FOUND);
        }

        $avg = Rating::where('rated', $id)->avg('stars');

        return response()->json([
            'status' =>  'Success',
            'message' => 'Average calculated',
            'data' => [
                'average' => $avg
            ]
        ], Response::HTTP_OK);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRatingRequest  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function show(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRatingRequest  $request
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */

}
