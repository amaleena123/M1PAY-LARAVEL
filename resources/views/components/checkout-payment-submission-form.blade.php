<form action="{{ route('payment.process') }}" method="POST">
    @csrf
    <input type="hidden" name="merchantId" value="{{ env('KEYCLOAK_CLIENT_ID') }}">
    <input type="hidden" name="amount" value="{{ $custOrderAmt }}"> 
    <input type="hidden" name="currency" value="{{ $custOrderCurr }}">
    <input type="hidden" name="orderid" value="{{ $custOrderId }}">
    <input type="hidden" name="cust_email" value="{{ $custEmail  }}">
    <input type="hidden" name="cust_mobile" value="{{ $custMobile  }}">
    <input type="hidden" name="description" value="{{ $description  }}">
    <input type="hidden" name="payment_option" value="{{ $paymentOption }}">
    <input type="hidden" name="channel" value="{{ $bank }}">
    <input type="hidden" name="exchangeOrderNo" value="{{ $custOrderId }}">
    <input type="hidden" name="skipConfirmation" value="false">
    
    <!-- This button can be removed if the form is submitted programmatically -->
    <button type="submit" id="pay_now">Pay Now</button>
</form>
