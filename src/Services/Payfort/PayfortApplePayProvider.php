<?php

namespace zakariatlilani\payfort\Services\Payfort;

use zakariatlilani\payfort\Services\AbstractProvider;
use zakariatlilani\payfort\Services\ProviderInterface;

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