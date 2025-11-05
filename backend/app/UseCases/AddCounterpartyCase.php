<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Dto\AddCounterpartyDto;
use App\Facades\DaDataApi;
use App\Models\User;
use App\Services\CounterpartyDataMapper;

class AddCounterpartyCase
{
    public function __construct(
        private CounterpartyDataMapper $counterpartyDataMapper
    ) {
    }

    public function __invoke(User $user, AddCounterpartyDto $dto)
    {
        $data = DaDataApi::findByInn($dto->inn);

        $counterpartyDto = $this->counterpartyDataMapper->mapFromExternalApi($data);
        return $user->counterparties()->create($counterpartyDto->toArray());
    }
}
