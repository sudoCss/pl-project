<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use App\Models\User;
use App\Http\Requests\StoreFavouriteRequest;
use App\Http\Requests\UpdateFavouriteRequest;
use Symfony\Component\HttpFoundation\Response;


class FavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = auth()->user()->id;
        $myFavourites = Favourite::where('user_id', $id)->join('users','users.id','=','favourites.expert')->select('users.id', 'firstName', 'lastName', 'image')->get();  //->join('users','users.id','=','favourites.expert')

        if(count($myFavourites) > 0)
        {
            return response()->json([
                'status' =>  'success',
                'message' => 'Get all favourites successfully',
                'data' => [
                    'Favourites' => $myFavourites
                ]
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' =>  'failed',
            'message' => 'No favourites yet',
            'data' =>  (object) []
        ], Response::HTTP_BAD_REQUEST);


    }

    public function store($id)
    {
        $user_id = auth()->user()->id;

        if(Favourite::where(['user_id' => $user_id, 'expert' => $id])->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => 'Favourite already exists',
                'data' => [
                    'Favourites' => (object) []
                ]
            ], Response::HTTP_BAD_REQUEST);
        }
        // foreach($myFavourites as $favourite)
        // {
        //     if($favourite->expert == $id)
        //     {
        //         $favourite->delete();

        //         return response()->json([
        //             'status' =>  'success',
        //             'message' => 'Favourite has been deleted',
        //             'data' => [
        //                 'Favourites' => (object) []
        //             ]
        //         ], Response::HTTP_OK);
        //     }
        // }

        $newFavourite = new Favourite();
        $newFavourite->user_id = auth()->user()->id;
        $newFavourite->expert = $id;
        $newFavourite->save();

        return response()->json([
            'status' =>  'success',
            'message' => 'Favourite has been created',
            'data' => [
                'Favourites' => $newFavourite
            ]
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $user_id = auth()->user()->id;

        if(!Favourite::where('id', $id)->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => 'Favourite not found',
                'data' => [
                    'Favourites' => (object) []
                ]
            ], Response::HTTP_BAD_REQUEST);
        }

        $favourite = Favourite::where('id', $id)->first();
        $favourite->delete();

        return response()->json([
            'status' =>  'success',
            'message' => 'Favourite has been deleted',
            'data' => (object) []
        ], Response::HTTP_OK);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFavouriteRequest  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */
    public function show(Favourite $favourite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFavouriteRequest  $request
     * @param  \App\Models\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFavouriteRequest $request, Favourite $favourite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */

}
