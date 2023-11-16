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


}
