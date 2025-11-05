<?php

namespace App\Http\Resources;

use App\Models\Counterparty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Counterparty
 */
class CounterpartyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'short_name' => $this->short_name,
            'inn' => $this->inn,
            'ogrn' => $this->ogrn,
            'address' => $this->address,
        ];
    }
}
