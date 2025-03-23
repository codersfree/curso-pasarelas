<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class WebhookController extends Controller
{
    public function mercadopago(Request $request)
    {
        if ($request->post('type') === 'payment') {
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
            $client = new PaymentClient();

            $payment = $client->get($request->get('data_id'));

            if ($payment->status === 'approved') {
                //Realizamos una accion en nuestra web
                Log::info('Pago aprobado');
            }
        }
    }
}
