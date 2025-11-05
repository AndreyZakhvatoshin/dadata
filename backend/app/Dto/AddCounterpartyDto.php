<?php

declare(strict_types=1);

namespace App\Dto;

use Spatie\LaravelData\Data;

class AddCounterpartyDto extends Data
{
    public function __construct(
        public string $inn,
    ) {
    }
}
