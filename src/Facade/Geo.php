<?php

namespace GeoService\Facade;

use GeoService\Service\GeoService;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin GeoService
 */
class Geo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GeoService::class;
    }
}