<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->user()->id)
        ->select('transactions.*')
        ->addSelect([
            'firstName' =>
            User::query()
            ->select(DB::raw('firstName'))
            ->whereColumn('users.id', 'transactions.expert')
        ])
        ->addSelect([
            'lastName' =>
            User::query()
            ->select(DB::raw('lastName'))
            ->whereColumn('users.id', 'transactions.expert')
        ])
        ->get();

        if(count($transactions) > 0)
        {
            return response()->json([
                'status' =>  'success',
                'message' => 'Get all transactions successfully',
                'data' => [
                    'Transactions' => $transactions
                ]
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' =>  'failed',
            'message' => 'No Transactions yet',
            'data' =>  (object) []
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransactionRequest  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
