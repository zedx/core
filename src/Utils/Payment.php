<?php

namespace ZEDx\Utils;

use Config;
use Crypt;
use Exception;
use Omnipay;
use Request;
use ZEDx\Gateways\Driver;
use ZEDx\Models\Gateway;
use ZEDx\Models\Order;
use ZEDx\Models\Transaction;

class Payment
{
    protected $order;

    public function __construct()
    {
    }

    /**
     * Purchase order.
     *
     * @param  array $transaction
     *
     * @return Response
     */
    public function purchase($transaction)
    {
        $this->order = null;
        $params = $this->getParams($transaction);
        $transaction = Omnipay::purchase($params);
        $response = $transaction->send();
        if ($response->getTransactionReference()) {
            $this->order->transaction()->update(['reference' => $response->getTransactionReference()]);
        }

        if ($response->isRedirect()) {
            $response->redirect();
        }

        return $response;
    }

    /**
     * Complete purchasing.
     *
     * @param  Order $order
     *
     * @return Response
     */
    public function completePurchase($order)
    {
        $this->order = $order;
        $transactionConfig = json_decode($this->order->transaction->data, true);
        $params = $this->getParams($transactionConfig, $this->order);
        $transaction = Omnipay::completePurchase($params);
        $transaction->setTransactionReference($this->order->transaction->reference);
        $transaction->setPayerId(Request::input('PayerID'));
        if ($transaction->getPayerId()) {
            $this->order->transaction()->update(['payerId' => $transaction->getPayerId()]);
        }

        $response = $transaction->send();

        return $response;
    }

    /**
     * Retrieve transaction params.
     *
     * @param  array $transaction
     *
     * @return array
     */
    protected function getParams($transaction)
    {
        $gatewayId = $transaction['gatewayId'];
        $gateway = Gateway::enabled()->findOrFail($gatewayId);
        if (! $this->order) {
            $this->createOrder($transaction, $gateway);
        }
        $gateways[$gateway->name] = [
            'driver'  => $gateway->driver,
            'options' => json_decode($gateway->options, true),
        ];

        Config::set('laravel-omnipay.gateways', $gateways);

        Omnipay::setGateway($gateway->name);

        $gatewayClass = "\ZEDx\Gateways\\".studly_case($gateway->driver);
        $driver = new $gatewayClass();
        if ($driver instanceof Driver === false) {
            throw new Exception("Driver [ $gatewayClass ] class must extend ZEDx\Gateways\Driver class");
        }

        $encryptedOrderId = Crypt::encrypt($this->order->id);
        $urls = [
            'transactionId' => $this->order->id,
            'cancelUrl'     => route('payment.cancel', $encryptedOrderId),
            'returnUrl'     => route('payment.return', $encryptedOrderId),
            'notifyUrl'     => route('payment.notify', $encryptedOrderId),
        ];

        $params = $driver->getConfig($transaction);

        return array_merge($params, $urls);
    }

    /**
     * Create order from a transaction.
     *
     * @param  array $transaction
     * @param  Gateway $gateway
     *
     * @return void
     */
    protected function createOrder($transaction, $gateway)
    {
        $transactionModel = Transaction::create([
            'item_id' => $transaction['item']['id'],
            'command' => $transaction['command'],
            'data'    => json_encode($transaction),
        ]);

        $this->order = new Order();
        $this->order->status = 'created';
        $this->order->name = $transaction['item']['name'];
        $this->order->quantity = $transaction['item']['quantity'];
        $this->order->gateway = $gateway->name;
        $this->order->driver = $gateway->driver;
        $this->order->amount = $transaction['item']['amount'];
        $this->order->user_id = $transaction['userId'];
        $this->order->transaction()->associate($transactionModel);
        $this->order->save();
    }
}
