<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\DaDataService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array findByInn(string $inn)
 */
class DaDataApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DaDataService::class;
    }
}
