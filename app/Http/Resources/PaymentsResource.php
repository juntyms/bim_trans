<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'attributes' => [
                'amount' => $this->amount,
                'paid_on' => $this->paid_on,
                'details' => $this->details
            ],
            'relationships' => [
                'transaction' => [
                    'id' => (string) $this->transaction->id,
                    'amount' => $this->transaction->amount,
                    'due_on' => $this->transaction->due_on,
                    'vat' => $this->transaction->vat,
                    'status' => $this->transaction->status->name
                ],
            ]
        ];
    }
}
