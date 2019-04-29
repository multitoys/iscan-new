<!DOCTYPE html>
<html>
<head>
    <title>Заказы</title>
    <style type="text/css">
        body {
            padding-top: 20px;
            padding-bottom: 20px;
            background-color: #ccc;
        }
        .row {
            height: 50px;
        }
        td a {
            color: #FFC107;
        }
        .btn-cansel {
            background-color: grey;
        }
        .btn-making {
            background-color: darkorange;
        }
        div.true, div.false {
            border-radius: 50%;
            width: 12px;
            border: 6px solid;
            height: 12px;
            margin: auto;
        }
        div.true {
            border-color: lightgreen;
        }
        div.false {
            border-color: red;
        }
        .back-red {
            background-color: red;
            color: white;
            text-align: right;
        }
        #main td {
            vertical-align: middle;
            padding: 3px;
        }
        .new-order {
            font-size: 1.4em!important;
        }
        form {
            margin-left: -15px!important;
        }
        .btn-white {
            color: black;
            background-color: white;
            border-color: black!important;
        }
    </style>
    <!-- Bootstrap -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-11">Вы авторизовались, как {{ auth()->user()->full_name }}</div>
        <div class="col-xs-1"><a href="/?page=logout">Выйти</a></div>
    </div>
</div>
<div class="container-fluid">
    <form action="{{ route('order.index') }}" method="get">
        <div class="col-xs-2">
            <select name="status" class="form-control" data-active="{{ $request->status }}">
                <option value="all">Bсе заказы</option>
                @foreach($statuses as $id => $status)
                <option value="{{ $id }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <select name="operator" class="form-control" data-active="{{ $request->user }}">
                <option value="all">Bсе операторы</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->last_name.' '.$user->first_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-3">
            <input type="text" name="search" class="form-control" placeholder="Клиент, телефон или Email" value="{{ $request->search }}">
        </div>
        <div class="col-xs-1">
            <button class="btn btn-info">ok</button>
        </div>
        <div class="col-xs-2"><a class="btn btn-white" href="/">Обновить страницу</a> </div>
        <div class="col-xs-2">
            <a class="btn btn-success new-order" href="/?page=order">Создать новый заказ</a>
        </div>
    </form>
    {{ $orders->links() }}
    <table id="main" class="table table-condensed table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Клиент</th>
            <th>Услуга</th>
            <th>Комментарий</th>
            <th><span class="glyphicon glyphicon-floppy-disk"></span></th>
            <th>Дата заказа</th>
            <th>Дата отгрузки</th>
            <th>Оператор</th>
            <th>СМС 1</th>
            <th>СМС 2</th>
            <th>Статус</th>
            <th>Доплата</th>
        </tr>
        </thead>
        @forelse ($orders as $order)
            @switch ($order->status_id)
                @case (1)
                    <tr class="btn-danger">
                    @break
                @case (2)
                    <tr class="btn-warning">
                    @break
                @case (3)
                    <tr class="btn-info">
                    @break
                @case (4)
                    <tr class="btn-making">
                    @break
                @case (5)
                    <tr class="btn-success">
                    @break
                @case (6)
                    <tr class="btn-default">
                    @break
                @case (7)
                    <tr class="btn-cansel">
                    @break
                @default
                    <tr>
                    @break
            @endswitch
            <td>{{ $order->id }}&nbsp;<a class="" href="{{ route('order.edit', ['order' => $order->id]) }}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
            <td>{{ $order->client->name }}</td>
            <td>{{ $order->service->name }}</td>
            <td>{{ $order->comment }}</td>
            <td>@if($order->is_files) <span class="glyphicon glyphicon-ok"></span> @endif</td>
            <td>{{ \Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</td>
            <td>{{ $order->date }}</td>
            <td>{{ isset($order->user) ? $order->user->full_name : '' }}</td>
            <td><div class="{{ isset($order->sms1) }}"></div></td>
            <td><div class="{{ isset($order->sms2) }}"></div></td>
            <td>
                {{ $order->status->name }}
                @if($order->status_id == 4)
                    <b>{{ $order->outsource->code }}</b>
                @endif
            </td>
            @if($order->surcharge > 0 && $order->status_id != 6)
                <td class="back-red">{{ $order->surcharge_formated }} </td>
            @else
                <td></td>
            @endif
        </tr>
        @empty
        @endforelse
    </table>
    {{ $orders->links() }}
</div>
<script src="http://code.jquery.com/jquery-latest.js" defer></script>
<script src="{{ asset('js/bootstrap.js') }}" defer></script>
</body>
</html>