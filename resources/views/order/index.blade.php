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
        div.status {
            position: relative;
            border-radius: 50%;
            width: 12px;
            border: 6px solid white;
            height: 12px;
            margin: auto;
        }
        div.status::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            width: 12px;
            border: 1px solid black;
            height: 12px;
            top: -6px;
            right: -6px;
        }
        div.sms-status-default {
            border-color: white;
        }
        div.sms-status-sent {
            border-color: yellow;
        }
        div.sms-status-delivered {
            border-color: lightgreen;
        }
        div.sms-status-not-delivered {
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
        ul.pagination {
            margin: 0;
        }
    </style>
    <!-- Bootstrap -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-8">Вы авторизовались, как {{ auth()->user()->full_name }}</div>
        @if(auth()->user()->role == \App\User::ADMIN)
            <div class="col-xs-3">
                <div class="dropdown">
                    <a id="dLabel" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle">
                        Настройки и отчеты
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <li><a href="{{ route('order.design_report') }}">Отчет по дизайну</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('user.index') }}">Операторы</a></li>
                        <li><a href="{{ route('service.index') }}">Услуги</a></li>
                        <li><a href="{{ route('paper.index') }}">Типы бумаги</a></li>
                        <li><a href="{{ route('outsource.index') }}">Производства</a></li>
                        <li><a href="{{ route('status.index') }}">Статусы заказов</a></li>
                    </ul>
                </div>
            </div>
        @endif
        <div class="col-xs-1">
            <a class="btn btn-default" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Выйти
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    <form action="{{ route('order.index') }}" method="get">
        <div class="col-xs-2">
            <select name="status" class="form-control" data-active="{{ $request->status }}">
                <option value="">Bсе заказы</option>
                @foreach($statuses as $status)
                <option value="{{ $status->id }}" {{ $request->status == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <select name="user" class="form-control" data-active="{{ $request->user }}">
                <option value="">Bсе операторы</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $request->user == $user->id ? 'selected' : '' }}>{{ $user->last_name.' '.$user->first_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-3">
            <input type="text" name="client" class="form-control" placeholder="Клиент, телефон или Email" value="{{ $request->client }}">
        </div>
        <div class="col-xs-1">
            <button class="btn btn-info">ok</button>
        </div>
        <div class="col-xs-2"><a class="btn btn-white" href="/">Обновить страницу</a> </div>
        <div class="col-xs-2">
            <a class="btn btn-success new-order" href="{{ route('order.create') }}">Создать новый заказ</a>
        </div>
    </form>
    {{ $orders->appends($request->all())->links() }}
    <table id="main" class="table table-condensed table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Клиент</th>
            <th>Услуга</th>
            <th>Комментарий</th>
            <th>Яч.</th>
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
            <td>{{ $order->client->name ?? '' }}</td>
            <td>{{ $order->service->name ?? '' }}</td>
            <td>{{ $order->comment }}</td>
            <td>{{ $order->place }}</td>
            <td>@if($order->is_files) <span class="glyphicon glyphicon-ok"></span> @endif</td>
            <td>{{ \Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</td>
            <td>{{ \Carbon::parse($order->date_end)->format('d.m.Y H:i') }}</td>
            <td>{{ isset($order->user) ? $order->user->full_name : '' }}</td>
            <td><div class="status {{ isset($order->sms1) ? $order->sms1->status_css : 'sms-status-default' }}"></div></td>
            <td><div class="status {{ isset($order->sms2) ? $order->sms2->status_css : 'sms-status-default'}}"></div></td>
            <td>
                {{ $order->status->name ?? '' }}
                @if($order->status_id == 4)
                    <b>{{ $order->outsource->code ?? '' }}</b>
                @endif
            </td>
            @if($order->surcharge != 0 && $order->status_id != 6)
                <td class="back-red">{{ $order->surcharge_formated }} </td>
            @else
                <td></td>
            @endif
        </tr>
        @empty
        @endforelse
    </table>
    {{ $orders->appends($request->all())->links() }}
</div>
<script src="{{ asset('js/jquery.js') }}" defer></script>
<script src="{{ asset('js/bootstrap.js') }}" defer></script>
</body>
</html>
