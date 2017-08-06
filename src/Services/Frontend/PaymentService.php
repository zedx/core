<?php

namespace ZEDx\Services\Frontend;

use Exception;
use Payment;
use ZEDx\Events\Payment\PaymentWasReceived;
use ZEDx\Gateways\Driver;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Models\Order;

class PaymentService extends Controller
{
    protected $driver;

    public function cancelPayment(Order $order)
    {
        $this->order = $order;
        $this->getDriver();
        $this->driver->cancelPayment();
        if ($order->status == 'created') {
            $order->status = 'cancelled';
            $order->save();
        }
    }

    public function notifyPayment(Order $order)
    {
        $this->order = $order;
        $this->getDriver();
        $this->driver->notifyPayment();
        if ($this->driver->isValid()) {
            $this->sendNotification();
            $order->transaction()->update(['response' => json_encode($response->getData())]);
            $this->execCommand();
        }
    }

    public function returnPayment(Order $order)
    {
        $this->order = $order;
        $response = Payment::completePurchase($order);
        $this->getDriver();
        $this->driver->returnPayment($response);
        if ($this->driver->isValid()) {
            $order->transaction()->update(['response' => json_encode($response->getData())]);
            $this->updateStatus();
            $this->sendNotification();
            $this->execCommand();

            return true;
        }

        return false;
    }

    public function accepted()
    {
        //
    }

    public function cancelled()
    {
        //
    }

    protected function execCommand()
    {
        $transaction = $this->order->transaction;
        $command = $transaction->command;

        if ($command) {
            dispatch(
                new $command($this->order)
            );
        }
    }

    protected function sendNotification()
    {
        if ($this->driver->getStatus() == 'completed') {
            event(
                new PaymentWasReceived($this->order, $this->order->gateway)
            );
        }
    }

    protected function updateStatus()
    {
        $this->order->status = $this->driver->getStatus();
        $this->order->gatewayStatus = $this->driver->getGatewayStatus();
        $this->order->save();
    }

    protected function getDriver()
    {
        $gatewayClass = "\ZEDx\Gateways\\".studly_case(strtolower($this->order->driver));
        $this->driver = new $gatewayClass();
        if ($this->driver instanceof Driver === false) {
            throw new Exception("Driver [ $gatewayClass ] class must extend ZEDx\Gateways\Driver class");
        }
    }
}
