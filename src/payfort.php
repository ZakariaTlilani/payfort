<?php

namespace ZakariaTlilani\PayFort;

use Illuminate\Support\Manager;
use InvalidArgumentException;
use ZakariaTlilani\PayFort\Services\HyperPay\HyperPayProvider;
use ZakariaTlilani\PayFort\Services\Payfort\PayfortProvider;
use ZakariaTlilani\PayFort\Services\Payfort\PayfortApplePayProvider;
use ZakariaTlilani\PayFort\Services\PaymentInterface;

class Payfort extends Manager implements PaymentInterface
{
    /**
     * @var string $merchant_reference
     */
    protected $merchant_reference;


    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @param  string  $merchant_reference
     * @return mixed
     */
    public function use($driver, $transaction_id = null)
    {
        $this->merchant_reference = $transaction_id;
        return $this->driver($driver);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \ZakariaTlilani\PayFort\Services\AbstractProvider
     */
    protected function createPayfortDriver()
    {
        $config = $this->app('config')['payments.payfort'];
        return $this->buildProvider(
            PayfortProvider::class,
            $config,
            $this->merchant_reference
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \ZakariaTlilani\PayFort\Services\AbstractProvider
     */
    protected function createPayfortApplePayDriver()
    {
        $config = $this->app('config')['payments.payfort_apple_pay'];

        return $this->buildProvider(
            PayfortApplePayProvider::class,
            $config,
            $this->merchant_reference
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \ZakariaTlilani\PayFort\Services\AbstractProvider
     */
    protected function createHyperPayDriver()
    {
        $config = $this->app('config')['payments.hyperPay'];

        return $this->buildProvider(
            HyperPayProvider::class,
            $config,
            $this->merchant_reference
        );
    }

    /**
     * Build a provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return \ZakariaTlilani\PayFort\Services\AbstractProvider
     */
    public function buildProvider($provider, $config, $merchant_reference)
    {
        return new $provider(
            $this->app('request'),
            $config,
            $merchant_reference
        );
    }


    /**
     * Get the default driver name.
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No Payment gateway was specified.');
    }
}
