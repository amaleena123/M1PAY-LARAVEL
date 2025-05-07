<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $keycloakUrl;
    protected $m1payUrl;
    protected $clientId;
    protected $clientSecret;
    protected $privateKeyPath;

    public function __construct()
    {
        $this->setupConfiguration();
    }

    protected function setupConfiguration()
    {
        $env = config('app.env') === 'production' ? 'PROD' : 'UAT';
        $this->clientId = env('KEYCLOAK_CLIENT_ID');
        $this->clientSecret = env('KEYCLOAK_CLIENT_SECRET');
        $this->privateKeyPath = env('M1PAY_PAYMENT_GATEWAY_PRIVATE_KEY_PATH');

        $this->keycloakUrl = env("KEYCLOAK_URL_{$env}");
        $this->m1payUrl = env("M1PAY_URL_REQ_{$env}");
        
        $this->m1payTxn = env("M1PAY_URL_RETRIEVE_TXN_{$env}");
    }

    public function obtainAccessToken()
    {
        $response = Http::asForm()->post($this->keycloakUrl, [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        /*\Log::debug('Token', [
        	'url' =>  $this->keycloakUrl,
    		'status' => $response->status(),
    		'body' => $response->body(),
	]);*/

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception('Failed to obtain access token');
    }

    public function signRequest($data)
    {
        $privKey = file_get_contents(storage_path($this->privateKeyPath));
        $pkeyid = openssl_get_privatekey($privKey);
        openssl_sign($data, $signature, $pkeyid, "sha1WithRSAEncryption");
        openssl_free_key($pkeyid);

        return bin2hex($signature);
    }

    public function sendPaymentRequest($params)
    {
        $accessToken = $this->obtainAccessToken();

	//dd([$params,$accessToken]);

	$response = Http::withHeaders([
    		'Authorization' => 'Bearer ' . trim($accessToken),
    		'Content-Type' => 'application/json',
                'X-Content-Type-Options' => 'nosniff',
    		'Accept' => 'application/json',
    		'Cache-Control' => 'no-cache',
	])->post($this->m1payUrl, $params);

        \Log::debug('Response M1PAY', [
                'url' => $this->m1payUrl,
                'params' => $params,
                'status' => $response->status(),
                'data' => $response->body(),
        ]);

	////
	//echo "<br/> Token:: <br/><textarea cols=150 readonly=true>".$accessToken."</textarea></br></br>";

	if ($response->successful()) {
            return $response->body();
        }

        Log::error('Failed to send payment request to M1Pay', [
            'response' => $response->body(),
        ]);

        throw new \Exception('Payment request failed');
    }

    public function retrieveTransactionInfo($transactionId)
    {
        $accessToken = $this->obtainAccessToken();
        $authorization = 'Authorization: Bearer ' . trim($accessToken);
        $headers = [
            $authorization,
            "X-Content-Type-Options: nosniff",
            "Cache-Control: no-cache"
        ];

        $txnInfoUrl = "{$this->m1payTxn}/{$transactionId}";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $txnInfoUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = curl_exec($curl);
        curl_close($curl);

        if (!$data) {
            // Handle error; maybe log it or throw an exception
            return null;
        }
        
        $catered = [];
        $data = json_decode($data,1);
        foreach($data as $key => $value)
        {
            if($key === 'skipConfirmation' )
            {
                continue;
            }
            
            $covt_str = $this->camelCaseToSnakeCaseTxnInfo($key);
            $catered["$covt_str"] = $value;
        }
        
        //return transaction info
        return $catered;

    }
    
    protected function camelCaseToSnakeCaseTxnInfo($input)
    {
        // Pattern to identify where a lowercase letter is followed by an uppercase letter
        $pattern = '/([a-z])([A-Z])/';
        
        // Place an underscore between those two letters
        $replacement = '$1_$2';
        
        // Convert to lowercase after replacing
        $snakeCase = strtolower(preg_replace($pattern, $replacement, $input));
        
        return $snakeCase;
    }
}
