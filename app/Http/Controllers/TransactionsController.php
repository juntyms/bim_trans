<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TransactionsResource;
use App\Http\Requests\StoreTransactionRequest;

class TransactionsController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->is_admin === 1) {
            $transactions = Transaction::all();
        } else {
            $transactions = Transaction::where('user_id',Auth::user()->id)->get();
        }

        return TransactionsResource::collection(
            $transactions
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        if (Auth::user()->is_admin !== 1) {
            return $this->error('','Only Admin can create a transaction',401);
        }

        $request->validated($request->all());

        $transaction = Transaction::create([
            'amount' => $request->amount,
            'user_id' => $request->user_id,
            'due_on' => $request->due_on,
            'vat' => $request->vat,
            'is_vat' => $request->is_vat
        ]);

        return new TransactionsResource($transaction);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        if (Auth::user()->is_admin === 1) {
            return new TransactionsResource($transaction);
        }

        if (Auth::user()->id !== $transaction->user_id) {
            return $this->error('','You are not authorized to view this transaction.',401);
        }

        return new TransactionsResource($transaction);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
