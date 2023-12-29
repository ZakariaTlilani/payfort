<?php

namespace zakariatlilani\payfort\Services\Payfort;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use zakariatlilani\payfort\Traits\Payfort\ApplePay;
use zakariatlilani\payfort\Traits\Payfort\PayfortApi;

class PayfortApplePay
{
    use ApplePay, PayfortApi;


    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var url
     */
    protected $gatewayUrl = 'https://sbpaymentservices.payfort.com/FortAPI/paymentApi';

    /**
     * @var array
     */
    protected $config;

    /**
     * @param \Illuminate\Support\Facades\Request
     * @param array $config
     * @param string $merchantReference
     * @return void
     */
    public function __construct(Request $request, array $config, $merchantReference)
    {
        if (!Arr::get($config, 'sandboxMode')) {
            $this->gatewayUrl = 'https://paymentservices.payfort.com/FortAPI/paymentApi';
        }
        $request->request->add(['merchant_reference' => $merchantReference]);
        $request->request->add(['amount' => 60]);
        $this->request = $request;
        $this->config = $config;
    }



    /**
     * Handle the payment via Apple Pay
     *
     * @return array
     */

    public function processRequest()
    {
        $params = $this->getDataParams();
        $params['signature'] = $this->calculateSignature($params, 'request');
        return (array) $this->callApi($params, $this->gatewayUrl);
    }
}