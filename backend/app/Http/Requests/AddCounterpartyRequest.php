<?php

namespace App\Http\Requests;

use App\Dto\AddCounterpartyDto;
use Illuminate\Foundation\Http\FormRequest;

class AddCounterpartyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'inn' => 'required|string',
        ];
    }

    public function toData(): AddCounterpartyDto
    {
        return AddCounterpartyDto::from($this->validated());
    }
}
