<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class DashboardController extends Controller
{
    public function index()
    {
        $formToken = $this->generateFormToken();
        $sessionToken = $this->generateSessionToken();
        $preferenceId = $this->generatePreferenceId();

        return view('dashboard', compact('formToken', 'sessionToken', 'preferenceId'));
    }

    public function generateFormToken()
    {
        $auth = base64_encode(config('services.izipay.clien_id') . ':' . config('services.izipay.client_secret'));

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $auth,
            'Content-Type' => 'application/json',
        ])
            ->post(config('services.izipay.url'), [
                'amount' => 10000,
                'currency' => 'USD',
                'orderId' => Str::random(10),
                'customer' => [
                    'email' => auth()->user()->email,
                ]
            ])
            ->json();

        return $response['answer']['formToken'];
    }

    public function generateSessionToken()
    {
        $auth = base64_encode(config('services.niubiz.user') . ':' . config('services.niubiz.password'));

        $accessToken = Http::withHeaders([
            'Authorization' => 'Basic ' . $auth,
        ])
            ->get(config('services.niubiz.url_api') . '/api.security/v1/security')
            ->body();

        $seesionToken = Http::withHeaders([
            'Authorization' => $accessToken,
            'Content-Type' => 'application/json',
        ])
            ->post(config('services.niubiz.url_api') . '/api.ecommerce/v2/ecommerce/token/session/' . config('services.niubiz.merchant_id'), [
                'channel' => 'web',
                'amount' => 100,
                'antifraud' => [
                    'clientIp' => request()->ip(),
                    'merchantDefineData' => [
                        'MDD4' => auth()->user()->email,
                        'MDD21' => 0,
                        'MDD32' => auth()->id(),
                        'MDD75' => 'Registrado',
                        'MDD77' => now()->diffInDays(auth()->user()->created_at) + 1,
                    ],
                ]
            ])->json();

        return $seesionToken['sessionKey'];
    }

    public function generatePreferenceId()
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        $client = new PreferenceClient();
        $preference = $client->create([
            "items" => [
                [
                    "id" => "1234",
                    "title" => "Mi producto",
                    "quantity" => 1,
                    "unit_price" => 100
                ]
            ],
            "back_urls" => [
                "success" => route('gracias'),
                "failure" => route('gracias'),
                "pending" => route('gracias')
            ],
            "auto_return" => "approved",
            "notification_url" => "https://1836-2001-1388-18a1-3e79-88b9-16eb-4bb1-3e40.ngrok-free.app/webhooks/mercadopago"
            /* "notification_url" => route('webhooks.mercadopago'), */
        ]);

        return $preference->id;
    }
}
