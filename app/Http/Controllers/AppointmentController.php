<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Role;
use App\Models\Wallet;
use App\Models\Transaction;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appointments = Appointment::where('user_id', auth()->user()->id)->get();

        if(count($appointments) > 0)
        {
            return response()->json([
                'status' =>  'success',
                'message' => 'Get all appointments successfully',
                'data' => [
                    'appointments' => $appointments
                ]
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' =>  'failed',
            'message' => 'No Appointments yet',
            'data' =>  (object) []
        ], Response::HTTP_BAD_REQUEST);


    }

    public function schedule()
    {
        if(User::where(['id' => auth()->user()->id, 'role_id' => Role::where('name', 'Expert')->first()->id])->exists())
        {
            $schedule = Appointment::where('expert', auth()->user()->id)->get();

            if(count($schedule) > 0)
            {
                return response()->json([
                    'status' =>  'success',
                    'message' => 'Get schedule successfully',
                    'data' => [
                        'schedule' => $schedule
                    ]
                ], Response::HTTP_OK);
            }

            return response()->json([
                'status' =>  'failed',
                'message' => 'schedule is Empty',
                'data' =>  (object) []
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' =>  'failed',
            'message' => 'You are not expert',
            'data' => (object) []
        ], Response::HTTP_BAD_REQUEST);
    }

    public function store(Request $request, $id)
    {
        if(auth()->user()->id == $id)
        {
            return response()->json([
                'status' =>  'failed',
                'message' => "You can't have appointment with yourself",
                'data' => (object) []
            ], Response::HTTP_BAD_REQUEST);
        }

        $validateData = $request->validate([
            'startTime' => 'required',
            'endTime' => 'required',
            'day_id' => 'required'
        ]);

        $walletUser = Wallet::find(auth()->user()->wallet_id);
        $walletExpert = Wallet::find(User::find($id)->wallet_id);

        $quantityToPay = ($request->endTime - $request->startTime) * 100;

        if($walletUser->quantity < $quantityToPay)
        {
            return response()->json([
                'status' =>  'failed',
                'message' => "You don't have enough money",
                'data' => (object) []
            ], Response::HTTP_BAD_REQUEST);
        }

        $appointment = new Appointment();
        $appointment->user_id = auth()->user()->id;
        $appointment->expert = $id;
        $appointment->day_id = $request->day_id;
        $appointment->startTime= $request->startTime;
        $appointment->endTime = $request->endTime;
        $appointment->save();

        $transaction = new Transaction();
        $transaction->user_id = auth()->user()->id;
        $transaction->expert = $id;
        $transaction->appointment_id = $appointment->id;
        $transaction->quantity = $quantityToPay;
        $transaction->save();

        $walletUser->quantity -= $quantityToPay;
        $walletExpert->quantity += $quantityToPay;

        $walletUser->save();
        $walletExpert->save();

        return response()->json([
            'status' =>  'success',
            'message' => 'Appointment has been created',
            'data' => [
                'appointment' => $appointment
            ]
        ], Response::HTTP_OK);

    }

    public function destroy($id)
    {
        if(!Appointment::find($id)->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => "Not found",
                'data' => (object) []
            ], Response::HTTP_BAD_REQUEST);
        }

        if(!Appointment::where(['id' => $id, 'user_id' => auth()->user()->id])->exists())
        {
            return response()->json([
                'status' =>  'failed',
                'message' => "You don't have permission to delete this appointment",
                'data' => (object) []
            ], Response::HTTP_BAD_REQUEST);
        }

        $appointment = Appointment::find($id);
        $transaction = Transaction::where('appointment_id', $id)->first();
        $walletUser = Wallet::find(auth()->user()->wallet_id);
        $walletExpert = Wallet::find(User::find($appointment->expert)->wallet_id);

        $walletUser->quantity += $transaction->quantity;
        $walletExpert->quantity -= $transaction->quantity;

        $transaction->delete();
        $appointment->delete();

        $walletUser->save();
        $walletExpert->save();

        return response()->json([
            'status' =>  'success',
            'message' => "your appointment has been deleted",
            'data' => (object) []
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAppointmentRequest  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAppointmentRequest  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */

}
