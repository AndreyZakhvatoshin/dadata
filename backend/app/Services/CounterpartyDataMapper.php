<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\CounterpartyDto;

class CounterpartyDataMapper
{
    public function mapFromExternalApi(array $data): CounterpartyDto
    {
        $collection = collect($data);
        $result = $collection
            ->map(function ($item) {
                return [
                    'short_name' => $item['data']['name']['short_with_opf'] ?? null,
                    'ogrn' => $item['data']['ogrn'] ?? null,
                    'address' => $item['data']['address']['unrestricted_value'] ?? null,
                    'inn' => $item['data']['inn'] ?? null,
                ];
            })
            ->first();

        return CounterpartyDto::from($result);
    }
}
