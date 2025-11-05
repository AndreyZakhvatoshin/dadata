<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\EmptyResponceException;
use Dadata\DadataClient;

class DaDataService
{
    public DadataClient $client;

    public function __construct()
    {
        $token = config('dadata.api_key');
        $secret = config('dadata.api_secret');

        $this->client = new DadataClient($token, $secret);
    }

    public function findByInn(string $inn): array
    {
        $result = $this->client->findById('party', $inn, 1);

        if (empty($result)) {
            throw new EmptyResponceException();
        }

        return $result;
    }
}
