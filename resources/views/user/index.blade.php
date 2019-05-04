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
        <a href="{{ route('order.index') }}">На главную</a>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <form action="{{ route('user.store') }}" method="post">
                {{ csrf_field() }}
                {{ method_field('POST') }}
                <div class="form-group">
                    Добавить оператора
                </div>
                <div class="form-group">
                    <input name="login" type="text" placeholder="Новый логин" class="form-control" value="{{ old('login') }}">
                </div>
                <div class="form-group">
                    <input name="password" type="password" placeholder="Пароль" class="form-control">
                </div>
                <div class="form-group">
                    <input name="last_name" type="text" placeholder="Фамилия" class="form-control" value="{{ old('last_name') }}">
                </div>
                <div class="form-group">
                    <input name="first_name" type="text" placeholder="Имя" class="form-control" value="{{ old('first_name') }}">
                </div>
                <div class="form-group">
                    <select name="role" class="form-control">
                        <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>Оператор</option>
                        <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Администратор</option>
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