<!-- resources/views/checkout-detail.blade.php -->
<x-checkout-layout>
    <x-slot name="title">Process Page</x-slot>
    <x-slot name="content">
        <h3>Confirming Payment Info</h3>
    	<p><strong>Order ID:</strong> {{ $custOrderId }}</p>
    	<p><strong>Order Amount (Currency):</strong> {{ $custOrderAmt }}({{ $custOrderCurr }})</p>
    	<p><strong>First Name:</strong> {{ $custFirstName }}</p>
    	<p><strong>Last Name:</strong> {{ $custLastName }}</p>
    	<p><strong>Mobile:</strong> {{ $custMobile }}</p>
    	<p><strong>Email:</strong> {{ $custEmail }}</p>
    	<p><strong>Description:</strong> {{ $description }}</p>
    	<p><strong>Payment Option:</strong> {{ $paymentOptText }}</p>
        <p><strong>Bank(Online Banking):</strong> {{ $bank }}</p>

	<x-checkout-payment-submission-form :custOrderId="$custOrderId"
                           	   :custOrderAmt="$custOrderAmt"
                                   :custOrderCurr="$custOrderCurr"
                                   :custFirstName="$custFirstName"
                                   :custLastName="$custLastName"
                                   :custMobile="$custMobile"
                                   :custEmail="$custEmail"
                                   :description="$description"
                                   :paymentOption="$paymentOption"
                                   :bank="$bank" 
        />

	<br>
	<a href="{{ route('checkout.show') }}"> Back To Checkout Page</a>
    </x-slot>
</x-checkout-layout>
