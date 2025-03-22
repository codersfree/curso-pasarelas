<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaidController extends Controller
{
    public function izipay(Request $request)
    {
        if ($request->get('kr-hash-algorithm') !== 'sha256_hmac') {
            throw new \Exception('Invalid hash algorithm');
        }

        $krAnswer = str_replace('\/', '/', $request->get('kr-answer'));

        $calculateHash = hash_hmac('sha256', $krAnswer, config('services.izipay.hash_key'));

        if ($calculateHash !== $request->get('kr-hash')) {
            throw new \Exception('Invalid hash');
        }

        return "OK";
    }

    public function niubiz(Request $request)
    {
        $auth = base64_encode(config('services.niubiz.user') . ':' . config('services.niubiz.password'));

        $accessToken = Http::withHeaders([
                'Authorization' => 'Basic ' . $auth,
            ])
            ->get(config('services.niubiz.url_api') . '/api.security/v1/security')
            ->body();

        $response = Http::withHeaders([
                'Authorization' => $accessToken,
                'Content-Type' => 'application/json',
            ])
            ->post(config('services.niubiz.url_api') . "/api.authorization/v3/authorization/ecommerce/" . config('services.niubiz.merchant_id'), [
                "channel" => "web",
                "captureType" => "manual",
                "countable" => true,
                "order" => [
                    "tokenId" => $request->transactionToken,
                    "purchaseNumber" => $request->purchasenumber,
                    "amount" => $request->amount,
                    "currency" => config('services.niubiz.currency'),
                ]
            ])
            ->json();

        if (isset($response['dataMap']) && $response['dataMap']['ACTION_CODE'] === '000') {
            // Pago se realizó satisfactoriamente
        }else{
            // Pago no se realizó
        }
        

        return $response;
    }
}
