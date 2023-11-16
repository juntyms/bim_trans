<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsResource extends JsonResource
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
                'amount' => (string)$this->amount,
                'user_id' => (string)$this->user_id,
                'due_on' => $this->due_on,
                'vat' => (string)$this->vat,
                'is_vat' => (string)$this->is_vat,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ],
            'relationships' => [
                'payer' => [
                    'id' => (string)$this->payer->id,
                    'name' => $this->payer->name,
                    'email' => $this->payer->email,
                    'is_admin' => (string)$this->payer->is_admin
                ],
                'status' => [
                    'id' => (string) $this->status->id,
                    'name' => $this->status->name,
                    'description' => $this->status->description
                ]
            ]
        ];
    }
}
