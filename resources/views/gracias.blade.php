<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('niubiz'))
            
                @php
                    $data = session('niubiz')['response'];
                    $purchaseNumber = session('niubiz')['purchaseNumber'];
                @endphp

                <div class="mb-4">
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                        <span class="font-medium">{{$data['dataMap']['ACTION_DESCRIPTION']}}</span>
                    </div>

                    <p>
                        <b>Número de pedido: </b> {{$purchaseNumber}}
                    </p>

                    <p>
                        <b>Fecha y hora de pedido: </b> {{ now()->createFromFormat('ymdHis', $data['dataMap']['TRANSACTION_DATE'])->format('d-m-Y H:i:s') }}
                    </p>

                    <p>
                        <b>Tarjeta: </b> {{$data['dataMap']['CARD']}} ({{$data['dataMap']['BRAND']}})
                    </p>

                    <p>
                        <b>Importe pagado: </b> {{$data['order']['amount']}} {{$data['order']['currency']}}
                    </p>
                </div>

            @endif

            <img src="https://www.shutterstock.com/shutterstock/photos/1957641682/display_1500/stock-vector-sticker-gracias-por-tu-compra-hecho-a-mano-means-thank-you-for-your-order-handmade-product-in-1957641682.jpg" alt="">
        </div>
    </div>
</x-app-layout>