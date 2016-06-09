<?php

namespace ZEDx\Gateways;

class PayPalExpress extends Driver
{
    public function getConfig($transaction)
    {
        return array_only($transaction['item'], ['description', 'amount', 'currency']);
    }

    public function returnPayment($response)
    {
        $data = $response->getData();
        if ($data['ACK'] == 'Success') {
            $status = $data['PAYMENTINFO_0_PAYMENTSTATUS'];
            $this->setGatewayStatus($status);
            $this->setStatus($this->getStatusFromRequest());
            $this->isValid = true;
        }
    }

    // Do something when payment was cancelled
    public function cancelPayment()
    {
    }

    public function notifyPayment()
    {
    }

    protected function getStatusFromRequest()
    {
        switch ($this->getGatewayStatus()) {
            case 'Completed':
                $status = 'completed';
                break;
            case 'Pending':
            case 'In-Progress':
                $status = 'pending';
                break;
            default:
                $status = 'cancelled';
                break;
        }

        return $status;
    }
}
