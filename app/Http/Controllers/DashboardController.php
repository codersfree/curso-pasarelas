<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $formToken = $this->generateFormToken();

        return view('dashboard', compact('formToken'));
    }

    public function generateFormToken()
    {
        $auth = base64_encode(config('services.izipay.client_id') . ':' . config('services.izipay.client_secret'));

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $auth,
        ])->post(config('services.izipay.url'), [
            'amount' => 10000,
            'currency' => 'USD',
            'orderId' => Str::random(10),
            'customer' => [
                'email' => auth()->user()->email,
            ]
        ])->json();

        return $response['answer']['formToken'];
    }
}
