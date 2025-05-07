<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Models\PaymentResponse;
use App\Models\CheckoutPayment;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function processPayment(Request $request)
    {
        // Validate the request inputs
        $validated = $request->validate([
            'merchantId' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'orderid' => 'required|string',
            'cust_email' => 'required|email',
            'cust_mobile' => 'required|string',
            'description' => 'required|string',
            'payment_option' => 'nullable|string',
            'channel' => 'nullable|string'
        ]);

        // Prepare parameters for the payment request
        $params = [
            'merchantId' => $validated['merchantId'],
            'transactionAmount' => number_format($validated['amount'], 2, '.', ''),
            'transactionCurrency' => $validated['currency'],
            'merchantOrderNo' => $validated['orderid'],
            'emailAddress' => $validated['cust_email'],
            'phoneNumber' => $validated['cust_mobile'],
            'productDescription' => $validated['description'],
            'exchangeOrderNo' => '', //$validated['orderid'] (Note: can leave it empty),
            'skipConfirmation' => "true",
            'channel' => $request->input('payment_option', ''),
            'fpxBank' => $request->input('channel', '')
        ];

	if(empty($params['channel'])){
	    unset($params['channel']);
	}

        if(empty($params['fpxBank'])){
            unset($params['fpxBank']);
        }

        //Sign the request
        $raw_data = $params['productDescription'].'|'
                 .$params['transactionAmount'].'|'
                 .$params['exchangeOrderNo'].'|'
                 .$params['merchantOrderNo'].'|'
                 .$params['transactionCurrency'].'|'
                 .$params['emailAddress'].'|'
                 .$params['merchantId'];

        $signature = $this->paymentService->signRequest($raw_data);

        $params['signedData'] = $signature;

        try {
            // Send the payment request
            $response = $this->paymentService->sendPaymentRequest($params);

            // Redirect to M1Pay
            return redirect()->to($response);

        } catch (\Exception $e) {
            Log::error('PaymentGatewayController::requestPayment failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send payment request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function responsePayment(Request $request)
    {

        //dd($request->query('transactionId'));

        $transactionId = $request->query('transactionId');

        $transactionInfo = $this->paymentService->retrieveTransactionInfo($transactionId);

    	    // Code to execute payment
            if ($transactionInfo === null) {
                // Handle error, e.g., return an error response or view
                return response()->json(['error' => 'Failed to fetch transaction info'], 500);
            }

            // Save the transaction info to the database
            $paymentResponse = PaymentResponse::create($transactionInfo);

            // Update the order status and state
            $payment = CheckoutPayment::where('cust_orderid', $transactionInfo['merchant_order_no'])->first();

            if ($payment && (in_array($transactionInfo['transaction_status'], array("CAPTURED","APPROVED")))) {
                // Update the status and state based on the payment response
                $payment->update([
                    'status_order' => 'Processing',
                    'state_order' => 'payment_received'
                ]);

                $transaction_id = $transactionInfo['transaction_id'];
                $order_id = $transactionInfo['merchant_order_no'];

                //Redirect to Result Page
                return redirect()->route('payment.result', ['orderId' => $order_id, 'transactionId' => $transaction_id]);

            } else {
                // Handle the case where the payment record does not exist
                abort(404, 'Payment record not found or Payment is pending');
            }
    }

    public function showResultPage($orderId, $transactionId)
    {
       // Log the received parameters for debugging
       Log::info('Received orderId', ['orderId' => $orderId]);
       Log::info('Received transactionId', ['transactionId' => $transactionId]);

       $transactionInfo = PaymentResponse::where('transaction_id', $transactionId)->first();
       $payment = CheckoutPayment::where('cust_orderid', $orderId)->first();

       if ($transactionInfo && $payment) {
            return view('payment.result', [
                "transaction_id" => $transactionInfo['transaction_id'],
                "order_id" => $transactionInfo['merchant_order_no'],
                "order_status" => $payment['status_order'],
                "order_state" => $payment['state_order'],
                "currency" => $transactionInfo['transaction_currency'],
                "amount" => $transactionInfo['transaction_amount'],
            ]);
        } else {
            abort(404, 'Transaction or payment record not found.');
        }
    }
}
