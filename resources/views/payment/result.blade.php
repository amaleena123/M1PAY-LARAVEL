<!-- resources/views/payment/result.blade.php -->
<x-checkout-layout>
    <x-slot name="title">Payment Result</x-slot>
    <x-slot name="content">
        <h1>Payment Result</h1>
            <br>
            <x-payment-detail label="Order ID" :value="$order_id" />
            <x-payment-detail label="Order Status" :value="$order_status" />
            <x-payment-detail label="Order State" :value="$order_state" />
            <x-payment-detail label="Currency" :value="$currency" />
            <x-payment-detail label="Amount" :value="$amount" />
            <x-payment-detail label="Transaction ID" :value="$transaction_id" />
            <br>
            <a href="{{ route('checkout.show') }}"> Back To Checkout Page</a>

    </x-slot>
</x-checkout-layout>
