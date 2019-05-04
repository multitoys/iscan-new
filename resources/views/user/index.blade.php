<!DOCTYPE html>
<html>
<head>
    <title>Операторы</title>
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
        <a href="{{ route('order.index') }}">На главную</a>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <form action="{{ route('order.index') }}" method="post">
                {{ csrf_field() }}
                {{ method_field('POST') }}
                <div class="form-group">
                    Добавить оператора
                </div>
                <div class="form-group">
                    <input name="login" type="text" placeholder="Новый логин" class="form-control">
                </div>
                <div class="form-group">
                    <input name="password" type="password" placeholder="Пароль" class="form-control">
                </div>
                <div class="form-group">
                    <input name="last_name" type="text" placeholder="Фамилия" class="form-control">
                </div>
                <div class="form-group">
                    <input name="firs_tname" type="text" placeholder="Имя" class="form-control">
                </div>
                <div class="form-group">
                    <select name="role" class="form-control">
                        <option value="2">Оператор</option>
                        <option value="1">Администратор</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" value="Добавить" class="btn btn-info">
                </div>
            </form>
        </div>
        <div class="col-xs-6">
            <ol>
                @foreach($users as $user)
                    <li>
                        {{ $user->full_name }} ({{ $user->login }}) - {{ $user->role_name }}
                        @if($user->id != auth()->user()->id)
                            <span><a href="#{{ $user->id }}" class="del">&times;</a></span>
                            <form id="{{ $user->id }}" action="{{ route('user.destroy', ['user' => $user->id]) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                        @endif
                    </li>
                @endforeach
            </ol>
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