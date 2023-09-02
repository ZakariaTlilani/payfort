<?php

namespace ZakariaTlilani\PayFort\Http\Controllers\Payment;

use Illuminate\Http\Request;
use ZakariaTlilani\PayFort\Facades\Payment;
use ZakariaTlilani\PayFort\Http\Controllers\Controller;

class TransactionController extends Controller
{

    public function __construct()
    {
        //
    }



    /**
     * After TOKINIZATION payfort return to this endpoint with an response
     *
     * @param \Illuminate\Http\Request
     * @param string $provider
     * @return void
     */
    public function paymentResponse(Request $request, $provider)
    {
        return Payfort::use($provider)->responseCallback();
    }

    /**
     * handle the response returned from payfort.
     *
     * @param \Illuminate\Http\Request
     * @param string $provider
     * @return mixed
     */
    public function processPaymentPresponse(Request $request, $provider)
    {
        return Payfort::use($provider)->processResponseCallback();
    }


    /**
     * handle the response returned from payfort.
     *
     * @param \Illuminate\Http\Request
     * @param string $provider
     * @return void
     */
    public function webHookNotify(Request $request, $provider)
    {
        return Payfort::use($provider)->webHook();
    }
}
