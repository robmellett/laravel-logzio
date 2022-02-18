<?php

namespace RobMellett\Logzio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RobMellett\Logzio\Logzio
 */
class Logzio extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-logzio';
    }
}
