<!-- resources/views/components/checkout-form.blade.php -->
<form method="POST" action="{{ route('checkout.detail') }}">
    @csrf
    <div class="mb-3">
        <label for="cust_first_name" class="form-label">First Name</label>
        <input type="text" class="form-control" id="cust_first_name" name="cust_first_name" required>
    </div>
    <div class="mb-3">
        <label for="cust_last_name" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="cust_last_name" name="cust_last_name" required>
    </div>
    <div class="mb-3">
        <label for="cust_email" class="form-label">Email</label>
        <input type="email" class="form-control" id="cust_email" name="cust_email" required>
    </div>
    <div class="mb-3">
        <label for="cust_mobile" class="form-label">Mobile</label>
        <input type="text" class="form-control" id="cust_mobile" name="cust_mobile" required>
    </div>
    <div class="mb-3">
        <label for="cust_orderid" class="form-label">Order ID</label>
        <input type="text" class="form-control" id="cust_orderid" name="cust_orderid" value="{{ $custOrderId }}">
    </div>
    <div class="mb-3">
        <label for="cust_orderamt" class="form-label">Order Amount</label>
        <input type="text" class="form-control" id="cust_orderamt" name="cust_orderamt" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3">Buy item</textarea>
    </div>
    <div class="input-group mb-3">
        <x-checkout-payment-options-select />
    </div>
    <div class="input-group mb-3">
        <x-checkout-banklist-fpx-select :banks="$banks"/>
    </div>
    <input type="hidden" id="cust_ordercurr" name="cust_ordercurr" value="MYR">
    <button type="submit">Pay Now</button>
</form>
