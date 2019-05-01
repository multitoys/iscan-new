@foreach($clients as $client)
    <li
            data-id    ="{{ $client->id }}"
            data-phone ="{{ $client->phone }}"
            data-client="{{ $client->name }}"
            data-email ="{{ $client->email }}"
            data-ready ="{{ $client->ordersReady->count() }}"
    >{{ $client->$field.' '.$client->name }}</li>
@endforeach