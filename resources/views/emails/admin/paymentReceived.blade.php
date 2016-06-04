@extends('emails.admin.layout')
@section('content')
{!! trans('email.admin.payment_received.content', ['amount' => $amount, 'gateway' => $gateway, 'currency' => $currency]) !!}
@endsection
