<?php

namespace ZakariaTlilani\PayFort\Services\Payfort;

use ZakariaTlilani\PayFort\Services\AbstractProvider;
use ZakariaTlilani\PayFort\Services\ProviderInterface;

class PayfortApplePayProvider extends AbstractProvider implements ProviderInterface
{


    /**
     *
     */
    public function pay()
    {
        return (new PayfortApplePay($this->request, $this->config, $this->merchantReference))->processRequest();
    }

    /**
     *
     */
    public function responseCallback()
    {
    }

    /**
     *
     */
    public function webHook()
    {
    }

    /**
     *
     */
    public function getMerchantReference()
    {
    }
}
