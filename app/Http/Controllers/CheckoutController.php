<?php
// app/Http/Controllers/CheckoutController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BanklistFpxService;
use App\Models\CheckoutPayment;

class CheckoutController extends Controller
{
    protected $banklistFpxService;

    public function __construct(BanklistFpxService $banklistFpxService)
    {
        $this->banklistFpxService = $banklistFpxService;
    }

    public function show()
    {
	$custOrderId = 'Z'.date('YmdHis');
        $bank_list = $this->banklistFpxService->getBankList();
        return view('checkout', ['custOrderId' => $custOrderId, 'banks' => $bank_list]);
    }

    public function processdetail(Request $request)
    {
	// Validate the request data
    	$validatedData = $request->validate([
        	'cust_orderid' => 'required|string',
        	'cust_orderamt' => 'required|numeric',
        	'cust_ordercurr' => 'required|string',
        	'cust_first_name' => 'required|string',
        	'cust_last_name' => 'required|string',
        	'cust_mobile' => 'required|string',
        	'cust_email' => 'required|email',
        	'description' => 'nullable|string',
        	'payment_option' => 'nullable|string',
                'bank' => 'nullable|string',
    	]);
        
        //Reinitiate variable after validated data
        $request = $validatedData;
        
        // Access the validated data
        $custOrderId = $request['cust_orderid'];
        $custOrderAmt = $request['cust_orderamt'];
        $custOrderCurr = $request['cust_ordercurr'];
        $custFirstName = $request['cust_first_name'];
        $custLastName = $request['cust_last_name'];
        $custMobile = $request['cust_mobile'];
        $custEmail = $request['cust_email'];
        $description = $request['description'];
        $paymentOption = $request['payment_option'];
        $bank = $request['bank'];


        //Create a new CheckoutPayment record
        $checkoutPayment = new CheckoutPayment([
            'cust_orderid' => $request['cust_orderid'],
            'cust_orderamt' => $request['cust_orderamt'],
            'cust_ordercurr' => $request['cust_ordercurr'],
            'cust_first_name' => $request['cust_first_name'],
            'cust_last_name' => $request['cust_last_name'],
            'cust_mobile' => $request['cust_mobile'],
            'cust_email' => $request['cust_email'],
            'description' => $request['description'],
            'payment_option' => $request['payment_option'],
            'bank' => $request['bank'],
        ]);

        $checkoutPayment->save();

	// Map the form value to user-friendly text
        $optionsMap = [
            'card_payment' => 'Card',
            'online_banking' => 'Online Banking',
            'touch_n_go' => 'Touch n Go',
            'boost' => 'Boost',
            'shopee_pay' => 'ShopeePay',
        ];

        $selectedOptionText = $optionsMap[$paymentOption] ?? ''; // Fallback to 'Unknown' if not found

        // Return a response or render a view for the detail page
        return view('checkout-detail', [
            'custOrderId' => $custOrderId,
            'custOrderAmt' => $custOrderAmt,
            'custOrderCurr' => $custOrderCurr,
            'custFirstName' => $custFirstName,
            'custLastName' => $custLastName,
            'custMobile' => $custMobile,
            'custEmail' => $custEmail,
            'description' => $description,
            'paymentOption' => strtoupper($paymentOption),
            'paymentOptText' => $selectedOptionText,
            'bank' => $bank, 
        ]);
    }
}
