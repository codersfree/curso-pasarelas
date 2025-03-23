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

        session()->flash('niubiz', [
            'response' => $response,
            'purchaseNumber' => $request->purchasenumber,
        ]);

        if (isset($response['dataMap']) && $response['dataMap']['ACTION_CODE'] === '000') {
            // Pago se realizó satisfactoriamente

            return redirect()->route('gracias');

        }else{
            
            return redirect()->route('dashboard');

        }
        

        return $response;
    }

    public function createPaypalOrder()
    {
        $accessToken = $this->generateAccessToken();
        $url = config('services.paypal.url') . '/v2/checkout/orders';

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post($url, [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => '100.00',
                    ],
                ],
            ]
        ])->json();

        return $response;
    }

    public function capturePaypalOrder(Request $request)
    {
        $orderId = $request->orderId;

        $accessToken = $this->generateAccessToken();
        $url = config('services.paypal.url') . '/v2/checkout/orders/' . $orderId . '/capture';

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post($url, [
            'intent' => 'CAPTURE',
        ])->json();

        if (!isset($response['status']) || $response['status'] !== 'COMPLETED') {
            throw new \Exception('Order not completed');
        }

        //Por aqui puedes realizar acciones en tu sitio web

        return $response;
    }

    public function generateAccessToken()
    {
        $auth = base64_encode(config('services.paypal.client_id') . ':' . config('services.paypal.secret'));
        $url = config('services.paypal.url') . '/v1/oauth2/token';

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $auth,
        ])->asForm()->post($url, [
            'grant_type' => 'client_credentials',
        ])->json();

        return $response['access_token'];
    }

    
}
