<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentsResource;
use App\Http\Requests\StorePaymentRequest;

class PaymentsController extends Controller
{
    const PAID = 1;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        // record payment
        $request->validated($request->all());

        $payment = Payment::create($request->all());

        //check transaction and update status
        if ($payment->transaction->amount >= $payment->transaction->loadSum('payments','amount')->payments_sum_amount) {
            $payment->transaction->update([
                'status_id' => Self::PAID
            ]);
        }

        return new PaymentsResource($payment);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
