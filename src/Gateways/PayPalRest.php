<?php

namespace ZEDx\Gateways;

class PayPalRest extends Driver
{
    public function getConfig($transaction)
    {
        $config = [
            'name'        => $transaction['item']['name'],
            'description' => $transaction['item']['description'],
            'amount'      => $transaction['item']['amount'],
            'items'       => [
                [
                    'quantity'    => $transaction['item']['quantity'],
                    'name'        => $transaction['item']['name'],
                    'price'       => number_format($transaction['item']['amount'] / $transaction['item']['quantity'], 2, '.', ''),
                    'description' => $transaction['item']['description'],
                    'currency'    => $transaction['item']['currency'],
                ],
            ],
            'currency' => $transaction['item']['currency'],
        ];

        return $config;
    }

    public function returnPayment($response)
    {
        $data = $response->getData();
        if ($data['state'] == 'approved') {
            $this->setGatewayStatus('approved');
            $this->setStatus('completed');
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
