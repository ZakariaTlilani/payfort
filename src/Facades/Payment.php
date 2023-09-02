<?php

namespace ZakariaTlilani\PayFort\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ZakariaTlilani\PayFort\Skeleton\SkeletonClass
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
