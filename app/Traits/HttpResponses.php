<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HttpResponses {

    protected function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'Request was successful.',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error($data, $message = null, $code)
    {
        return response()->json([
            'status' => 'Error has occurred.',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    private function transactionAuthorization($transaction)
    {
        if (1 != Auth::user()->is_admin && Auth::user()->id != $transaction->user_id ) {
            return $this->error('','You are not authorized.',401);
        }
    }

    private function adminAuthorization()
    {

        if (1 != Auth::user()->is_admin) {
            return $this->error('','Only Admin can create a transaction',401);
        }

    }
}
