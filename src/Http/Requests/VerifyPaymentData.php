<?php

namespace zakariatlilani\payfort\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// use LVR\CreditCard\CardCvc;
// use LVR\CreditCard\CardNumber;
// use LVR\CreditCard\CardExpirationYear;
// use LVR\CreditCard\CardExpirationMonth;

class VerifyPaymentData extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'card_number' => ['required', new CardNumber],
            // 'expiration_year' => ['required', new CardExpirationYear($this->get('expiration_month') ?: '')],
            // 'expiration_month' => ['required', new CardExpirationMonth($this->get('expiration_year') ?: '')],
            // 'cvc' => ['required', new CardCvc($this->get('card_number') ?: '')],
            // 'hold_name' => 'required|min:2|max:55',
            // 'amount' => 'required|min:1'
        ];
    }
}