<?php

namespace zakariatlilani\payfort\Services\HyperPay;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use zakariatlilani\payfort\Services\AbstractProvider;
use zakariatlilani\payfort\Services\ProviderInterface;
use zakariatlilani\payfort\Traits\HyperPay\CreditCardBrand;
use zakariatlilani\payfort\Traits\HyperPay\Helpers;
use zakariatlilani\payfort\Traits\HyperPay\interfaceCreditCard;
use GuzzleHttp\Client;


class HyperPayProvider  extends AbstractProvider implements ProviderInterface, interfaceCreditCard
{

    use CreditCardBrand, Helpers;

    /**
     *
     */
    public function pay()
    {
        $card_number = $this->request->get('card_number');
        $payment_brand = strtoupper($this->getBrand($card_number));

        $data  = [
            "entityId" => Arr::get($this->config, 'entityId'),
            "currency" => Arr::get($this->config, 'currency'),
            "merchantTransactionId" => $this->merchantReference,
            "paymentBrand" => $payment_brand,
            "paymentType" => "DB",
            "amount" => $this->request->get('amount'),
            "customer.email" => $this->request->get('email'),
            "card.number" => $card_number,
            "card.holder" => $this->request->get('hold_name'),
            "card.expiryMonth" => $this->request->get('expiration_month'),
            "card.expiryYear" => $this->request->get('expiration_year'),
            "card.cvv" => $this->request->get('cvc'),
            "shopperResultUrl" => url('/api/hyperPay/response')
        ];

        Log::info($data);
        $client = new Client();

        $res = $client->request('POST', 'https://test.oppwa.com/v1/payments', [
            'form_params' => $data,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Bearer ' . Arr::get($this->config, 'token'),
            ]
        ]);

        $res = (array) json_decode($res->getBody(), true);
        if (Arr::has($res, 'redirect')) {
            $redirect_parameters = (array) Arr::get($res, 'redirect');
            Log::info(['redirect_params' => $redirect_parameters]);
            $parameters = $this->getParameters($redirect_parameters['parameters']);
            return response()->json(['redirect' => $redirect_parameters['url'] . $parameters]);
        }

        return $this->hyperPayresponse($res);
    }


    /**
     *
     */
    public function responseCallback()
    {

        Log::info(['callback' => $this->request->all()]);
        $client = new Client(['base_uri' => 'https://test.oppwa.com/']);
        $response = $client->request('GET', $this->request->get('resourcePath'), [
            'query' => [
                "entityId" => Arr::get($this->config, 'entityId')
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . Arr::get($this->config, 'token'),
            ]
        ]);

        $response = (array) json_decode($response->getBody(), true);
        return $this->hyperPayresponse($response);
    }


    /**
     *
     */
    public function getMerchantReference()
    {
        return $this->request->get('merchantTransactionId');
    }

    /**
     *
     */
    public function webHook()
    {
    }
}