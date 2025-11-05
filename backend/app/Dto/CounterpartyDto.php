<?php

declare(strict_types=1);

namespace App\Dto;

use Spatie\LaravelData\Data;

class CounterpartyDto extends Data
{
    public function __construct(
        public string $inn,
        public string $short_name,
        public string $ogrn,
        public string $address
    ) {
    }
}
