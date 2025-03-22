<x-app-layout>

    @push('head')
        <script type="text/javascript"
            src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
            kr-public-key="{{ config('services.izipay.public_key') }}"
            kr-post-url-success="{{route('paid.izipay')}}" ;></script>

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
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-2">
                    <p>
                        Monto a pagar
                    </p>

                    <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                        role="alert">
                        <span class="font-medium">100 USD</span> Change a few things up and try submitting again.
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
                            <button class="w-full flex justify-center bg-gray-100 py-2 rounded-lg shadow">
                                <img class="h-8" src="https://proveedores.niubiz.com.pe/assets/media/logos/logo.png"
                                    alt="">
                            </button>
                        </li>

                        {{-- PayPal --}}
                        <li x-data="{ open: false }">
                            <button class="w-full flex justify-center bg-gray-100 py-2 rounded-lg shadow"
                                x-on:click="open = !open">
                                <img class="h-8" src="https://codersfree.com/img/payments/paypal.png" alt="">
                            </button>

                            <div class="pt-6 mb-4" x-show="open" style="display: none;">
                                <p>Aquí se mostrará las opciones de PayPal</p>
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
</x-app-layout>
