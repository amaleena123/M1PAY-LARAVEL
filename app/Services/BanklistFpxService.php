<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BanklistFpxService
{
    public function getBankList()
    {
        $env = config('app.env') === 'production' ? 'PROD' : 'UAT';

        $headers = [
            'Content-Type: application/json',
            'X-Content-Type-Options:nosniff',
            'Accept:application/json',
            'Cache-Control:no-cache'
        ];

        $banklist_url = match ($env) {
            'UAT' => 'https://gateway.m1payall.com/m1payfpx/api/bank-list/B2C',
            'PROD' => 'https://gateway.m1pay.com.my/fpx/api/bank-list/B2C',
        };

        $response = Http::withHeaders($headers)->get($banklist_url);

        if ($response->status() === 200) {
            $bank_list = $response->json();
            return $bank_list;
        }

	$bank_list = array(0=>array("bankId"=>"NONE","title"=>"None Bank","fpxOnline"=>0)); //when fpx banklist failed
        return $bank_list;
    }
}
