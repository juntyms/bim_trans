<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TransactionsResource;
use App\Http\Requests\StoreTransactionRequest;


class TransactionsController extends Controller
{
    use HttpResponses;

    const PAID = 1;
    const OUTSTANDING = 2;
    const OVERDUE = 3;

    /**
     * @OA\GET(
     *  tags={"Transaction"},
     *  path="/api/v1/transactions",
     *  summary="Display all transaction per user or all by admin",
     *  security={ {"bearerToken": {}} },
     *  @OA\Response(response=200, description="OK"),
     * )
     *
     * Display a listing of the resource.
     */
    public function index()
    {
        if (1 === Auth::user()->is_admin) {
            $transactions = Transaction::all();
        } else {
            $transactions = Transaction::where('user_id',Auth::user()->id)->get();
        }

        return TransactionsResource::collection(
            $transactions
        );
    }

    /**
     * @OA\POST(
     *  tags={"Transaction"},
     *  path="/api/v1/transactions",
     *  summary="Save Transaction",
     *  security={ {"bearerToken": {}} },
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"amount","user_id","due_on","vat","is_vat"},
     *              @OA\Property(
     *                  property="amount",
     *                  type="float",
     *              ),
     *              @OA\Property(
     *                  property="user_id",
     *                  type="integer",
     *              ),
     *              @OA\Property(
     *                  property="due_on",
     *                  type="string",
     *                  format="date"
     *              ),
     *              @OA\Property(
     *                  property="vat",
     *                  type="float",
     *              ),
     *              @OA\Property(
     *                  property="is_vat",
     *                  type="float",
     *              ),
     *          ),
     *      ),
     *  ),
     *  @OA\Response(response=200, description="OK")
     * )
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        if (1 !== Auth::user()->is_admin) {
            return $this->error('','Only Admin can create a transaction',401);
        }

        $request['status_id'] = $this->getStatus($request->due_on);

        $request->validated($request->all());

        $transaction = Transaction::create([
            'amount' => $request->amount,
            'user_id' => $request->user_id,
            'due_on' => $request->due_on,
            'vat' => $request->vat,
            'is_vat' => $request->is_vat,
            'status_id' => $request->status_id
        ]);

        return new TransactionsResource($transaction);
    }

    private function getStatus($date_due)
    {

        $due_date = new Carbon($date_due); //Check date

        if (0 >= Carbon::now()->diffInDays($due_date->endOfDay() , false)) {

            return Self::OVERDUE; // Overdue

        } else {

            return Self::OUTSTANDING; // Outstanding

        }
    }

    /**
     * @OA\GET(
     *  tags={"Transaction"},
     *  path="/api/v1/transactions/{transaction}",
     *  security={ {"bearerToken":{}} },
     *  summary="Show Transaction",
     *      @OA\Parameter(
     *          name="transaction",
     *          in="path",
     *          description="transaction id",
     *          required=true,
     *      ),
     *  @OA\Response(response=200,description="OK"),
     *  @OA\Response(response=401,description="Unauthorized")
     * )
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return $this->transactionAuthorization($transaction) ? $this->transactionAuthorization($transaction) : new TransactionsResource($transaction);
    }


    /**
     * @OA\PATCH(
     *  tags={"Transaction"},
     *  path="/api/v1/transactions/{transaction}",
     *  security={ {"bearerToken":{}} },
     *  summary="Edit Transaction",
     *      @OA\Parameter(
     *          name="transaction",
     *          in="path",
     *          description="transaction id",
     *          required=true,
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"amount","user_id","due_on","vat","is_vat"},
     *                  @OA\Property(
     *                      property="amount",
     *                      type="float",
     *                  ),
     *                  @OA\Property(
     *                      property="user_id",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="due_on",
     *                      type="string",
     *                      format="date",
     *                      example="2022-07-01",
     *                  ),
     *                  @OA\Property(
     *                      property="vat",
     *                      type="float",
     *                  ),
     *                  @OA\Property(
     *                      property="is_vat",
     *                      type="float",
     *                  ),
     *              ),
     *          ),
     *      ),
     *  @OA\Response(response=200,description="OK"),
     *  @OA\Response(response=401,description="Unauthorized")
     * )
     *
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {

        if ($this->transactionAuthorization($transaction)) {

            return $this->transactionAuthorization($transaction);
        }

        $request['status_id'] = $this->getStatus($transaction->due_on);

        $transaction->update($request->all());

        return new TransactionsResource($transaction);

    }

    /**
     * @OA\DELETE(
     *  tags={"Transaction"},
     *  path="/api/v1/transactions/{transaction}",
     *  security={ {"bearerToken":{}} },
     *  summary="Delete Transaction",
     *      @OA\Parameter(
     *          name="transaction",
     *          in="path",
     *          description="transaction id",
     *          required=true,
     *      ),
     *  @OA\Response(response=200,description="OK"),
     *  @OA\Response(response=401,description="Unauthorized")
     * )
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        return $this->transactionAuthorization($transaction) ? $this->transactionAuthorization($transaction) : $transaction->delete();
    }

    private function transactionAuthorization($transaction)
    {
        if (1 !== Auth::user()->is_admin && Auth::user()->id !== $transaction->user_id ) {
            return $this->error('','You are not authorized.',401);
        }
    }
}
