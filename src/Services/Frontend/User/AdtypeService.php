<?php

namespace ZEDx\Services\Frontend\User;

use Auth;
use Payment;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\AdtypePurchaseRequest;
use ZEDx\Models\Adtype;
use ZEDx\Models\Gateway;

class AdtypeService extends Controller
{
    /**
     * Auth User.
     *
     * @var \ZEDx\Models\User
     */
    protected $user;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::user();

        if ($this->user && !$this->user->is_validate) {
            redirect()->route('user.edit')->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = $this->user;

        $currency = setting('currency');

        $adtypes = Adtype::all();
        $numbers = $user->adtypes()->lists('number', 'adtype_id');

        return [
            'data' => compact('user', 'adtypes', 'numbers', 'currency'),
        ];
    }

    public function cart(Adtype $adtype)
    {
        $currency = setting('currency');
        $gateways = Gateway::enabled()->get();

        if ($adtype->price > 0) {
            return [
                'data' => compact('adtype', 'currency', 'gateways'),
            ];
        }

        return [
            'data' => null,
        ];
    }

    public function checkout(Adtype $adtype, AdtypePurchaseRequest $request)
    {
        $user = $this->user;
        $quantity = $request->get('quantity');

        $transaction = [
            'gatewayId' => $request->get('gateway'),
            'userId'    => $user->id,
            'command'   => '\ZEDx\Jobs\purchaseAdtype',
            'item'      => [
                'id'          => $adtype->id,
                'amount'      => number_format($adtype->price * $quantity, 2, '.', ''),
                'name'        => 'Annonce '.$adtype->title,
                'description' => 'Annonce '.$adtype->title,
                'currency'    => setting('currency'),
                'quantity'    => $quantity,
            ],
        ];
        Payment::purchase($transaction);
    }
}
