# About Payfort package by Zakaria Tlilani

This package provide an easy payment system using **Amazon Payfort** in Middle East and North Africa.

**Note:** This package requires a minimum Laravel 5.5

## Installation

1.  You can install the package via composer:

```bash
composer require ZakariaTlilani/PayFort
```

2. Run the command below to publish the package config file `config/payfort.php`:

```bash
php artisan vendor:publish --provider="ZakariaTlilani\PayFort\PaymentServiceProvider" --tag="config"
```

## Configuration

Before start using Payfort as a payment gateway, you will add the credentiels to `app/config/payfort.php` ( preferbly use .env vars rather then hard coding it) as seen bellow.

```php

<?php

  'payfort' => [

        /**
         *  Routing return URLs, on success || failure
         */

        'callback_urls' => [
            'error-page' => '/api/error', // redirect to error page
            'success-page' => '/api/success', // redirect to success page
        ],

        /**
         *  Account specific settings (you are going to get from you Payfort account)
         */

        'sandboxMode'        => env('PAYFORT_SAND_BOX_MODE', true),
        'merchantIdentifier' => env('MERCHANT_IDENTIFIER', ''),
        'accessCode'         => env('ACCESS_CODE', ''),
        'SHARequestPhrase'   => env('SHA_REQUEST_PASSPHRASE', ''),
        'SHAResponsePhrase'  => env('SHA_RESPONSE_PASSPHRASE', ''),
        'SHAType'            => env('SHA_TYPE', 'sha256'),
        'command'            => env('COMMAND', 'AUTHORIZATION'),
        
        /**
         *  General usage settings
         */

        'language'           => env('LANGUAGE', 'en'),    // used currency ex: english
        'currency'           => env('CURRENCY', 'SAR')    // used currency ex: saudi arabian riyal
    ]


  'payfort_apple_pay' => [

        'sandboxMode'        => env('PAYFORT_SAND_BOX_MODE', true),
        'merchantIdentifier' => env('MERCHANT_IDENTIFIER', ''),
        'accessCode'         => env('ACCESS_CODE', ''),
        'SHARequestPhrase'   => env('SHA_REQUEST_PASSPHRASE', ''),
        'SHAResponsePhrase'  => env('SHA_RESPONSE_PASSPHRASE', ''),
        'SHAType'            => env('SHA_TYPE', 'sha256'),
        'command'            => env('COMMAND', 'PURCHASE'),

        'language'           => env('LANGUAGE', 'en'),
        'currency'           => env('CURRENCY', 'SAR')
  ]
```
Note : dont forget to create route to submit data to the backend.


## Usage

# Required Parameters

Each request should contains `amount`, `email`, `hold_name`

```php

<?php

  $request->add([
    'amount' => '',
    'email' => '',
    'hold_name' => ''
  ])

```
# Payfort Usage Example
 
```php

<?php

  use Illuminate\Http\Request;

  Route::post('/pay', function (Request $request) {

    /**
     * you can add to your controller or route to use the gateway.
    */

    $payment_type = payfort_apple_pay || payfort // use one of these parameters as a string.

    $merchant_reference = Payment::use('payfort')->generateMerchantReference();

    return Payfort::use($payment_type, $merchant_reference)->pay();

  });
```

## Payfort Events

As we know payfort can notify the merchant, for all events you subscribed for on an transaction. we need the first to add your callback into payfort dashboard, then you can implement this callback in your application

```php

<?php

  use Illuminate\Http\Request;

  Route::match(['get', 'post'], '/payfort-callback', function(Request $request) {

    return Payment::use('payfort')->webHook();

  });

```

This webHook can invoks two events build in. There are two events available for you to listen for.

| Event                                        | Fired                                          | Parameter                                   |
| -------------------------------------------- | ---------------------------------------------- | ------------------------------------------- |
| `ZakariaTlilani\PayFort\Events\SuccessTransaction` | when payfort response with the successful data | array [success response](#success-response) |
| `ZakariaTlilani\PayFort\Events\FailTransaction`    | when payfort response with the Fail data       | array [fail_response](#fail-response)       |

### Success response

```php
[
  "response_code" => "18000",
  "card_number" => "400555******0001",
  "card_holder_name" => "CUSTOMER_HOLDER_NAME",
  "signature" => "d641d71c13da959cba92371d70c686b602e2b62796dfca5286c760c6b5d9e3b1",
  "merchant_identifier" => "YOUR_MERCHANT_IDENTIFIER",
  "expiry_date" => "2105",
  "access_code" => "YOUR_ACCESS_CODE",
  "language" => "ar",
  "service_command" => "TOKENIZATION",
  "response_message" => "عملية ناجحة",
  "merchant_reference" => "278245857",
  "token_name" => "dced12c0eeeb444185dcc450b917d987",
  "return_url" => "YOUR_RETURN_URL"
  "card_bin" => "400555"
  "status" => "18"
]

```

### Fail response

```php
[
"esponse_code" => "00016",
"card_number" => "400550******0001",
"card_holder_name" => "CUSTOMER_HOLDER_NAME",
"merchant_identifier" => "YOUR_MERCHANT_IDENTIFIER",
"expiry_date" => "2105",
"access_code" => "YOUR_ACCESS_CODE",
"language" => "ar",
"service_command" => "TOKENIZATION",
"response_message" => "رقم البطاقة غير صحيح",
"merchant_reference" => "158151963",
"return_url" => "YOUR_RETURN_URL",
"status" => "00",
"error_msg" => "رقم البطاقة غير صحيح",
]
```


# Pay With ReactNative

if you want to process the payment via webView in your react native mobile app

```js
function onNavigationStateChange() {
    // this method will be invoked each time the url changed
}

function onMessage() {
    // here you can handle the final result
}

return (
    <WebView
        source={{ html: html() }}
        onNavigationStateChange={onNavigationStateChange}
        javaScriptEnabled={true}
        domStorageEnabled={true}
        onMessage={onMessage}
        startInLoadingState
    />
);
```

and add this to your controller or route to be able to use the payment gateway using ReactNative

```php
<?php

  return Payment::use('payfort')->viaReactNative()->pay();
```
