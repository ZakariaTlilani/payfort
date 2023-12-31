<?php

namespace zakariatlilani\payfort\Services\Payfort;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use zakariatlilani\payfort\Traits\Payfort\PayfortServices;

class PayfortTokenization
{

    use PayfortServices;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request_data;

    /**
     * @var url
     */
    protected $gatewayUrl = 'https://sbcheckout.payfort.com/';

    /**
     * @var array
     */
    protected $config;


    /**
     * @var mixed
     */

    protected $merchantReference;
    /**
     * @param \Illuminate\Support\Facades\Request
     * @param array $config
     * @param string $merchantReference
     * @return void
     */
    public function __construct(Request $request, array $config, $merchantReference)
    {
        if (!Arr::get($config, 'sandboxMode')) {
            $this->gatewayUrl = 'https://checkout.payfort.com/';
        }
        $this->request_data = $request;
        $this->merchantReference = $merchantReference;
        $this->config = $config;

        if (is_null($request->get('amount'))) {
            throw new InvalidArgumentException("the amount is required !");
        }

        if (is_null($request->get('email'))) {
            throw new InvalidArgumentException("the customer email is required !");
        }

        if (is_null($request->get('hold_name'))) {
            throw new InvalidArgumentException("the customer hold_name is required !");
        }
    }


    /**
     * Generate Tokenization data
     *
     * @return array
     */
    public function processRequest()
    {
        $merchantPageData = $this->getMerchantPageData();
        $postData = $merchantPageData['params'];
        $gatewayUrl = $merchantPageData['url'];
        $form = $this->getPaymentForm($gatewayUrl, $postData);
        $form = ['form' => $form, 'url' => $gatewayUrl, 'params' => $postData];
        Cache::forever($postData['merchant_reference'], [
            'email' => $this->request_data->get('email'),
            'hold_name' => $this->request_data->get('hold_name'),
            'amount' => $this->request_data->get('amount'),
        ]);
        return $form;
    }

    /**
     *  get merchant data structure that should submited to payfort
     *
     *  @return array data
     */
    public function getMerchantPageData()
    {
        if (isset($_GET['3ds']) && $_GET['3ds'] == 'no') {
            $returnUrl = $this->getUrl(Arr::get($this->config, 'routes.payfort-response') . '?3ds=no');
        }
        $iframeParams              = array(
            'merchant_identifier' => Arr::get($this->config, 'merchantIdentifier'),
            'access_code'         => Arr::get($this->config, 'accessCode'),
            'merchant_reference'  => $this->merchantReference,
            'service_command'     => 'TOKENIZATION',
            'language'            => Arr::get($this->config, 'language'),
            'return_url'          => url('/api/payfort/response'),
        );

        $iframeParams['signature'] = $this->calculateSignature($iframeParams, 'request');

        $gatewayUrl = $this->gatewayUrl . 'FortAPI/paymentPage';
        $debugMsg = "Fort Merchant Page Request Parameters \n" . print_r($iframeParams, 1);
        $this->log($debugMsg);

        return array('url' => $gatewayUrl, 'params' => $iframeParams);
    }

    /**
     * Gnerate HTML form
     *
     * @param String getwayUrl
     * @param Array postData
     * @return HTML
     */
    public function getPaymentForm($gatewayUrl, $postData)
    {
        $form = '<form style="display:none" name="payfort_payment_form" id="payfort_payment_form" method="post" action="' . $gatewayUrl . '">';
        foreach ($postData as $k => $v) {
            $form .= '<input type="hidden" name="' . $k . '" value="' . $v . '">';
        }
        $form .= '<input type="submit" id="submit">';
        return $form;
    }
}