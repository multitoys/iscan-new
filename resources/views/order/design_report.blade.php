<!DOCTYPE html>
<html>
<head>
    <title>Отчет по стоимости дизайна</title>
    <style type="text/css">
        body {
            padding-top: 20px;
            padding-bottom: 20px;
            background-color: #ccc;
        }
        .row {
            height: 50px;
        }
    </style>
    <!-- Bootstrap -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <a href="{{ route('order.index') }}" class="btn btn-default">На главную</a>
    </div>
</div>
<div class="container">
    <form action="{{ route('order.design_report') }}" method="get">
        <div class="row">
            <div class="col-xs-1">Дата от</div>
            <div class="col-xs-2">
                <input name="date_from" required type="date" value="{{ $request->date_from }}" class="form-control">
            </div>
            <div class="col-xs-1">Дата до</div>
            <div class="col-xs-2">
                <input name="date_to" required type="date" value="{{ $request->date_to }}" class="form-control">
            </div>
            <div class="col-xs-1">
                <input type="submit" value="Отчёт" class="btn btn-info">
            </div>
        </div>
    </form>
</div>
<div class="container">
    @isset($total_sum)
    <div class="row">
        <div class="col-xs-2">Всего за период</div>
        <div class="col-xs-23"><b><?=isset($total_sum) ? number_format((int)$total_sum, 0, ',', ' ').' грн.' : ''?></b></div>
    </div>
    @endisset
    @isset($dates)
    @forelse($dates as $date => $sum)
    <div class="row">
        <div class="col-xs-2">{{ $date }}</div>
        <div class="col-xs-23"><b>@isset($sum) {{ number_format((int)$sum, 0, ',', ' ').' грн.' }} @endisset</b></div>
    </div>
    @empty
    @endforelse
    @endisset
</div>
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}" defer></script>
</body>
</html>