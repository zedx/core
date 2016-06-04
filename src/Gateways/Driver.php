<?php

namespace ZEDx\Gateways;

abstract class Driver
{
    protected $isValid = false;
  /**
   * status : 'cancelled', 'completed' or 'pending'.
   **/
  protected $status;

  // Status returned by the gateway
  protected $gatewayStatus;

    abstract public function getConfig($transaction);

    abstract public function returnPayment($response);

    abstract public function cancelPayment();

    abstract public function notifyPayment();

    public function isValid()
    {
        return $this->isValid;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setGatewayStatus($gatewayStatus)
    {
        $this->gatewayStatus = $gatewayStatus;
    }

    public function getGatewayStatus()
    {
        return $this->gatewayStatus;
    }
}
