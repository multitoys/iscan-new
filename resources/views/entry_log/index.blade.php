<!DOCTYPE html>
<html>
<head>
    <title>Производства</title>
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
@if ($errors->any())
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container">
    <div class="row">
        <a href="{{ route('order.index') }}" class="btn btn-default">На главную</a>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <p class="lead">Логи входа пользователей</p>
            {{ $logs->links() }}
            <table class="table table-condensed table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>IP адрес</th>
                    <th>Дата и Время входа</th>
                </tr>
                </thead>
                <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->user->full_name }}</td>
                        <td>{{ $log->ip }}</td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>
            {{ $logs->links() }}
        </div>
    </div>
</div>
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}" defer></script>
</body>
</html>
