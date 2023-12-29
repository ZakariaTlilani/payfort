<?php

namespace zakariatlilani\payfort\Services\Payfort;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use zakariatlilani\payfort\Services\Payfort\PayfortProcessResponse;
use zakariatlilani\payfort\Services\Payfort\PayfortTokenization;
use zakariatlilani\payfort\Services\Payfort\PayfortResponseRequest;
use zakariatlilani\payfort\Traits\Payfort\PayfortServices;
use zakariatlilani\payfort\Services\AbstractProvider;
use zakariatlilani\payfort\Services\ProviderInterface;
use zakariatlilani\payfort\Events\FailedTransaction;
use zakariatlilani\payfort\Events\SuccessTransaction;

class PayfortProvider  extends AbstractProvider implements ProviderInterface
{
    use PayfortServices {
        calculateSignature as protected calculate_signature;
    }




    /**
     *
     * @return $this
     */

    public function viaReactNative()
    {
        Cache::forever('reactNative_' . $this->merchantReference, true);
        return $this;
    }


    /**
     *
     */
    public function pay()
    {

        return (new PayfortTokenization($this->request, $this->config, $this->merchantReference))->processRequest();
    }


    /**
     *
     */
    public function responseCallback()
    {
        return (new PayfortResponseRequest($this->request, $this->config))->processMerchantPageResponse();
    }

    /**
     *
     */
    public function processResponseCallback()
    {
        return (new PayfortProcessResponse($this->request, $this->config))->processResponse();
    }

    /**
     *
     */
    public function getMerchantReference()
    {
        return $this->request->get('merchant_reference');
    }

    /**
     *
     */
    public function webHook()
    {

        if ($this->request->get('signature') === $this->calculateSignature($this->request->toArray(), 'response')) {
            if (substr($this->request->get('response_code'), 2) == '000') {

                return event(new SuccessTransaction($this->request->toArray()));
            }
            return event(new FailedTransaction($this->request->toArray()));
        }

        throw new InvalidArgumentException('Signature Mismatch.');
    }


    /**
     * @param array $params
     * @param string $type
     * @return string
     */
    public function calculateSignature(array $params, $type)
    {
        $validator = Validator::make(['type' => $type], ['type' => 'required|in:request,response']);
        if ($validator->fails()) {
            throw new ValidationException('The given data was invalid.');
        }

        if (Arr::has($params, 'signature')) {
            $params = Arr::except($params, ['signature']);
        }

        return $this->calculate_signature($params, $type);
    }
}