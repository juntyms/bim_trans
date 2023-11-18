<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PaymentsResource;
use App\Http\Requests\StorePaymentRequest;

class PaymentsController extends Controller
{
    use HttpResponses;

    const PAID = 1;

    /**
     * @OA\POST(
     *  tags={"Payment"},
     *  path="/api/v1/payments",
     *  summary="Add Payment",
     *  security={ {"bearerToken":{}} },
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"transaction_id","amount","paid_on"},
     *              @OA\Property(
     *                  property="transaction_id",
     *                  type="integer",
     *                  description="Transaction ID Number"
     *              ),
     *              @OA\Property(
     *                  property="amount",
     *                  type="float",
     *                  description="Amount"
     *              ),
     *              @OA\Property(
     *                  property="paid_on",
     *                  type="string",
     *                  format="date",
     *                  example="2023-11-20",
     *                  description="Transaction ID Number"
     *              ),
     *              @OA\Property(
     *                  property="details",
     *                  type="string",
     *                  description="Other Details"
     *              ),
     *          ),
     *      ),
     *    ),
     *  @OA\Response(response=200,description="OK"),
     *  @OA\Response(response=401,description="Unauthorized")
     *  )
     *
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        if ($this->adminAuthorization()) {
            return $this->adminAuthorization();
        }

        // record payment
        $request->validated($request->all());

        $payment = Payment::create($request->all());

        //check transaction and update status
        if ($payment->transaction->amount <= $payment->transaction->loadSum('payments','amount')->payments_sum_amount) {
            $payment->transaction->update([
                'status_id' => Self::PAID
            ]);
        }

        return new PaymentsResource($payment);
    }


}
