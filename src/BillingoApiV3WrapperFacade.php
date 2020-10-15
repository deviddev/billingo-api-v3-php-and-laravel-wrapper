<?php

namespace Deviddev\BillingoApiV3Wrapper;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Deviddev\BillingoApiV3Wrapper\Skeleton\SkeletonClass
 */
class BillingoApiV3WrapperFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        //return 'billingo';
        return 'billingo-api-v3-wrapper'; // in composer.json the extra laravel aliases is: BillingoApiV3Wrapper
    }
}
