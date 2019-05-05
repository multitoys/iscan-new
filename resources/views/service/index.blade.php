<!DOCTYPE html>
<html>
<head>
    <title>Услуги</title>
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
            <p class="lead">Услуги</p>
            <ol>
                @foreach($services as $service)
                    <li>
                        {{ $service->name }}
                        <span><a href="#{{ $service->id }}" class="del">&times;</a></span>
                        <form id="{{ $service->id }}" action="{{ route('service.destroy', ['service' => $service->id]) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>
                    </li>
                @endforeach
            </ol>
        </div>
        <div class="col-xs-6">
            <form action="{{ route('service.store') }}" method="post">
                {{ csrf_field() }}
                {{ method_field('POST') }}
                <div class="form-group">
                    Добавить услугу
                </div>
                <div class="form-group">
                    <input name="name" type="text" placeholder="Новая услуга" class="form-control" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <input type="submit" value="Добавить" class="btn btn-info">
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}" defer></script>
<script>
    $(document).ready(function () {
        $('a.del').on('click', function () {
            if (confirm('Удалить?')) {
                $('form' + $(this).attr('href')).submit();
            }
            return false;
        });
    });
</script>
</body>
</html>