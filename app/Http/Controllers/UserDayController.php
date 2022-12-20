<?php

namespace App\Http\Controllers;

use App\Models\UserDay;
use App\Http\Requests\StoreUserDayRequest;
use App\Http\Requests\UpdateUserDayRequest;

class UserDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserDayRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserDayRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserDay  $userDay
     * @return \Illuminate\Http\Response
     */
    public function show(UserDay $userDay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserDayRequest  $request
     * @param  \App\Models\UserDay  $userDay
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserDayRequest $request, UserDay $userDay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserDay  $userDay
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDay $userDay)
    {
        //
    }
}
