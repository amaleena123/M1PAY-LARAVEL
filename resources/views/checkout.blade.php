<!-- resources/views/checkout.blade.php -->
<x-checkout-layout>
    <x-slot name="title">Sample Checkout Payment</x-slot>
    <x-slot name="content">
        <h1>Checkout Payment - Demo</h1>
        <!-- Include the checkout form component and pass $custOrderId variable -->
        <x-checkout-form :custOrderId="$custOrderId" :banks="$banks" />
    </x-slot>
</x-checkout-layout>
