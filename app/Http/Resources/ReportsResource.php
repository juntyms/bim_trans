<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'month' => $this->trans_month,
                'year' => $this->trans_year,
                'paid' => $this->total_paid,
                'outstanding' => $this->total_outstanding,
                'overdue' => $this->total_overdue

        ];
    }
}
