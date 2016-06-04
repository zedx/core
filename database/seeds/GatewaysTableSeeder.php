<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Gateway;

class GatewaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paypalOption = '{"username":"","password":"","signature":"","testMode":true}';
        //$twocheckoutOptions = '{"accountNumber": "", "secretWord": "", "testMode": true}';
        //$coinbaseOptions    = '{"apiKey": "", "secret": "", "accountId": ""}';
        //$stripOptions       = '{"apiKey":""}';

        $this->create('paypal', 'Paypal', $this->getPaypalHelp(), $paypalOption, 'PayPal_Express', 1);
        //$this->create('twoCheckout', '2Checkout', '', $twocheckoutOptions, 'TwoCheckout', 1);
        //$this->create('coinbase', 'Coinbase', '', $coinbaseOptions, 'Coinbase', 0);
        //$this->create('stripe', 'Stripe', '', $stripOptions, 'Stripe', 0);
    }

    protected function create($name, $title, $help, $options, $driver, $enabled)
    {
        return Gateway::create([
            'name'    => $name,
            'title'   => $title,
            'help'    => $help,
            'options' => $options,
            'driver'  => $driver,
            'enabled' => $enabled,
        ]);
    }

    protected function getPaypalHelp()
    {
        return '<div class="alert alert-danger"><i class="fa fa-info-circle"></i> The following video explain how to get Paypal API credential in production environement so you have to <span class="label label-primary">Disable testMode</span></div>
            <div class="responsive-video">
              <iframe width="560" height="315" src="https://www.youtube.com/embed/weUVQPKZq98" frameborder="0" allowfullscreen></iframe>
            </div>';
    }
}
