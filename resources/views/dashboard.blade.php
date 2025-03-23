<x-app-layout>

    @push('head')
        <script type="text/javascript"
            src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
            kr-public-key="{{ config('services.izipay.public_key') }}" kr-post-url-success="{{ route('paid.izipay') }}" ;>
        </script>

        <!-- 3 : theme néon should be loaded in the HEAD section   -->
        <link rel="stylesheet" href="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/neon-reset.min.css">
        <script type="text/javascript" src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/neon.js"></script>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('niubiz'))

                @php
                    $data = session('niubiz')['response'];
                    $purchaseNumber = session('niubiz')['purchaseNumber'];
                @endphp
                
                <div class="mb-4">
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <span class="font-medium">{{$data['data']['ACTION_DESCRIPTION']}}</span>
                    </div>

                    <p>
                        <b>Número de pedido: </b> {{$purchaseNumber}}
                    </p>

                    <p>
                        <b>Fecha y hora de pedido: </b> {{ now()->createFromFormat('ymdHis', $data['data']['TRANSACTION_DATE'])->format('d-m-Y H:i:s') }}
                    </p>

                    <p>
                        <b>Tarjeta: </b> {{$data['data']['CARD']}} ({{$data['data']['BRAND']}})
                    </p>

                </div>

            @endif


            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-2">
                    <p>
                        Monto a pagar
                    </p>

                    <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                        role="alert">
                        <span class="font-medium">100 USD</span>
                    </div>
                </div>

                <div>
                    <p class="mb-4">Seleccione un método de pago</p>

                    <ul class="space-y-4">
                        {{-- Izipay --}}
                        <li x-data="{ open: false }">
                            <button class="w-full flex justify-center bg-[#FF4240] py-2 rounded-lg shadow"
                                x-on:click="open = !open">
                                <img class="h-8"
                                    src="https://www.tuposizipay.com/wp-content/uploads/2024/03/izipay-1.png"
                                    alt="">
                            </button>

                            <div class="pt-6 mb-4 flex justify-center" x-show="open" style="display: none;">

                                <div class="kr-embedded" kr-form-token="{{ $formToken }}">

                                </div>
                        </li>

                        {{-- Niubiz --}}
                        <li>
                            <button class="w-full flex justify-center bg-gray-100 py-2 rounded-lg shadow"
                                onclick="VisanetCheckout.open()">
                                <img class="h-8" src="https://proveedores.niubiz.com.pe/assets/media/logos/logo.png"
                                    alt="">
                            </button>
                        </li>

                        {{-- PayPal --}}
                        <li x-data="{ open: false }">
                            <button x-show="!open" class="w-full flex justify-center bg-gray-100 py-2 rounded-lg shadow"
                                x-on:click="open = !open">
                                <img class="h-8" src="https://codersfree.com/img/payments/paypal.png" alt="">
                            </button>

                            <div class="py-4" x-show="open" style="display: none;">
                                <div id="paypal-button-container"></div>
                            </div>
                        </li>

                        {{-- Mercado Pago --}}
                        <li x-data="{ open: false }">
                            <button class="w-full flex justify-center bg-gray-100 py-2 rounded-lg shadow"
                                x-on:click="open = !open">
                                <img class="h-8"
                                    src="https://spurgeon.ar/wp-content/uploads/2023/01/version-horizontal-large-logo-mercado-pago-1024x267.webp"
                                    alt="">
                            </button>
                        </li>

                        {{-- PayU --}}
                        <li x-data="{ open: false }">
                            <button class="w-full flex justify-center bg-gray-100 py-2 rounded-lg shadow"
                                x-on:click="open = !open">
                                <img class="h-8"
                                    src="https://www.contactcenterworld.com/images/company/PayU-Latam-600px-logo.png"
                                    alt="">
                            </button>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    @push('js')

        {{-- <script type="text/javascript" src="{{ config('services.niubiz.url_js') }}"></script> --}}
        <script type="text/javascript" src="https://static-content-qas.vnforapps.com/env/sandbox/js/checkout.js"></script>

        <script>

            document.addEventListener('DOMContentLoaded', function(event) {

                let purchasenumber = Math.floor(Math.random() * 1000000000);

                VisanetCheckout.configure({
                    sessiontoken: '{{ $sessionToken }}',
                    channel: 'web',
                    merchantid: "{{ config('services.niubiz.merchant_id') }}",
                    purchasenumber: purchasenumber,
                    amount: 100,
                    expirationminutes: '20',
                    timeouturl: "{{ route('dashboard') }}",
                    merchantlogo: 'img/comercio.png',
                    formbuttoncolor: '#000000',
                    action: "{{ route('paid.niubiz') }}" + "?purchasenumber=" + purchasenumber + "&amount=100",
                    complete: function(params) {
                        alert(JSON.stringify(params));
                    }
                });
            });
        </script>

        {{-- SDK PayPal --}}
        <script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=USD"></script>
        <script>
            paypal.Buttons({
                createOrder(){
                    return axios.post("{{ route('paid.createPaypalOrder') }}")
                        .then(res => {
                            return res.data.id;
                        })
                        .catch(err => {
                            console.error(err);
                        });
                },
                onApprove(data){
                    
                    return axios.post("{{ route('paid.capturePaypalOrder') }}", {
                        orderId: data.orderID
                    })
                    .then(res => {
                        window.location.href = "{{ route('gracias') }}";
                    })
                    .catch(err => {
                        console.error(err);
                    });

                },
            }).render('#paypal-button-container');
        </script>
    @endpush

</x-app-layout>
