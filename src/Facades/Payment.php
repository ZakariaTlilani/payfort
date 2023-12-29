<?php

namespace zakariatlilani\payfort\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \zakariatlilani\payfort\Skeleton\SkeletonClass
 */
class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'payment';
    }
}