<!DOCTYPE html>
<html>
<head>
    <title>Заказ</title>
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datepicker.css') }}" rel="stylesheet">
    <style type="text/css">
        body {
            padding-top: 20px;
            padding-bottom: 20px;
            background-color: #ecf7da;
            color: #003950;
        }

        .row {
            height: 50px;
        }

        input[type="number"] {
            width: 4em;
        }

        input[name="qty"] {
            width: 8em;
        }

        #message {
            color: red;
            font-weight: 700;
        }

        .row.textarea, .row.submit {
            height: 100%;
        }

        hr {
            border-top: 1px solid #5cc4ef;
        }

        div#search_results, div#search_results2, div#search_results3 {
            position: absolute;
            top: 35px;
            z-index: 10;
            /*width: 100%;*/
        }

        #search_results ul, #search_results2 ul, #search_results3 ul {
            border: 1px solid gray;
            background-color: white;
            border-radius: 4px;
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        #search_results li, #search_results2 li, #search_results3 li {
            padding: 7px 12px;
            margin: 0;
            border-bottom: 1px solid lightgray;
        }

        #search_results li:hover, #search_results2 li:hover, #search_results3 li:hover {
            background-color: #c7e9f7;
        }

        .wrapper {
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            display: none;
        }
        .file-upload input[type="file"]{
            position: absolute;
            width: 250px;
            height: 100px;
            opacity: 0;
        }
        .file-form-wrap{
            width:260px;
            margin:auto;
        }
        .file-upload {
            position: relative;
            overflow: hidden;
            width: 250px;
            height:100px;
            line-height:50px;
            background: #4169E1;
            border-radius: 10px;
            color: #fff;
            text-align: center;
        }
        .file-upload:hover {
            background: #1E90FF;
        }
        /* Растягиваем label на всю область блока .file-upload */
        .file-upload label {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        /* стиль текста на кнопке*/
        .file-upload span {
            font-weight:bold;
            display: block;
        }
        .preview-img{
            max-width:100px;
            max-height:100px;
            margin:5px;
        }
        .file-list li {position: relative;line-height: 32px;height: 32px;font-size: 1.1em;}

        span.del-file {
            position: absolute;
            top: 1px;
            left: -40px;
            cursor: pointer;
            display: block;
            font-size: 1.5em;
            padding: 0;
            color: red;
        }
        hr {
            margin-top: 7px;
            margin-bottom: 7px;
        }
        .order-id {
            font-size: 1.2em;
            color:red;
        }
    </style>
    <!-- Bootstrap -->
    <script src="http://code.jquery.com/jquery-latest.js" defer></script>
    <script src="{{ asset('js/datepicker.js') }}" defer></script>
</head>
<body>
<div class="container-fluid">
    <a href="{{ route('order.index') }}">На главную</a>
    <form action="{{ route('order.update', ['order' => $order->id]) }}" method="post"
          enctype="multipart/form-data"
          onsubmit="return (ValidPhone() && ValidClient() && ValidOperator() && ValidInputNumber() && ValidDate())">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="row">
            <div class="col-xs-2">НОМЕР ЗАКАЗА</div>
            <div class="col-xs-1"><b class="order-id">{{ $order->id }}</b></div>
            <div class="col-xs-2">Оператор</div>
            <div class="col-xs-3">
                <select name="user_id" class="form-control">
                    <option value="">Выбор оператора</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : ''}}>{{ $user->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xs-1">Статус заказа</div>
            <div class="col-xs-3">
                <select name="status_id" class="form-control">
                    @foreach ($statuses as $status)
                    <option value="{{ $status->id }}" {{ $order->status_id == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                    @endforeach
                </select>
                <select name="outsource_id" class="form-control" style="display:none;">
                    <option value="">Выберите производство</option>
                    @foreach ($outsources as $outsource)
                    <option value="{{ $outsource->id }}" {{ $order->outsource_id == $outsource->id ? 'selected' : ''}}>{{ $outsource->code }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-xs-1">Заказчик</div>
            <div class="col-xs-3">
                <input type="hidden" name="client_id" value="{{ $order->client_id ?? '' }}">
                <input name="client" id="client" type="text" class="form-control"
                       value="{{ $order->client->name ?? '' }}"
                       autocomplete="off">
                <div id="search_results3"></div>
            </div>
            <div class="col-xs-1">Моб. тел.</div>
            <div class="col-xs-3">
                <div class="input-group">
                    <span class="input-group-addon">38</span>
                    <input name="phone" id="phone" type="tel" class="form-control"
                           value="{{ $order->client->phone ?? '' }}"
                           autocomplete="off">
                    <div id="search_results"></div>
                </div>
            </div>
            <div class="col-xs-1">E-mail</div>
            <div class="col-xs-3">
                <div class="input-group">
                    <span class="input-group-addon">@</span>
                    <input name="email" id="email" type="text" class="form-control"
                           value="{{ $order->client->email ?? '' }}"
                           autocomplete="off">
                    <div id="search_results2"></div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-xs-3">Дата принятия заказа</div>
            <div class="col-xs-3">{{ \Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</div>
            <div class="col-xs-3">Способ оплаты</div>
            <div class="col-xs-3">
                <select name="pay_type" class="form-control">
                    <option value="1" {{ $order->pay_type == 1 ? 'selected' : '' }}>
                        Наличные
                    </option>
                    <option value="2" {{ $order->pay_type == 2 ? 'selected' : '' }}>
                        Счёт
                    </option>
                    <option value="3" {{ $order->pay_type == 3 ? 'selected' : '' }}>
                        Карта
                    </option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">Дата отгрузки заказа</div>
            <div class="col-xs-3">
                <input id="timepicker-actions-exmpl" name="date_end" type="text" class="form-control"
                       value="{{ isset($order->date_end) ? \Carbon::parse($order->date_end)->format('d.m.Y H:i') : '' }}">
            </div>
        </div>
        <hr>
        <div class="row submit">
            <div class="col-xs-5">
                <div class="row">
                    <div class="col-xs-4">Выбор услуги</div>
                    <div class="col-xs-8">
                        <select name="service_id" class="form-control">
                            @foreach ($services as $service)
                            <option value="{{ $service->id }}" {{ $order->service_id == $service->id ? 'selected' : ''}}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">Тираж</div>
                    <div class="col-xs-8">
                        <input id="qty" name="quantity" type="number" class="form-control" min="0"
                               value="{{ $order->quantity > 0 ? $order->quantity : '' }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">Цветность</div>
                    <div class="col-xs-2">
                        <label class="checkbox-inline"><input name="is_color"
                                                              type="checkbox" {{ $order->is_color > 0 ? 'checked' : '' }}>цвет</label>
                    </div>
                    <div class="col-xs-2">
                        <label class="checkbox-inline"><input name="is_non_color"
                                                              type="checkbox" {{ $order->is_non_color > 0 ? 'checked' : '' }}>чб</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">Тип бумаги</div>
                    <div class="col-xs-8">
                        <select name="paper_id" class="form-control">
                            @foreach ($papers as $paper)
                                <option value="{{ $paper->id }}" {{ $order->paper_id == $paper->id ? 'selected' : ''}}>{{ $paper->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-7">
                <div class="row textarea">
                    <div class="col-xs-3">Комментарий к заказу</div>
                    <div class="col-xs-9">
						<textarea name="comment" rows="7" class="form-control" placeholder="Комментарий к заказу"
                                  value="{{ $order->comment }}">{{ $order->comment }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row submit">
            <div class="col-xs-3">
                <div class="row">
                    <div class="col-xs-3">Сумма заказа</div>
                    <div class="col-xs-7">
                        <input type="hidden" name="amount" value="{{ $order->amount }}">
                        <input id="grn1" name="grn1" type="number" size="5"
                               value="{{ explode('.', $order->amount)[0] }}">.<input
                                id="kops1" name="kops1" type="number" size="2"
                                value="{{ count(explode('.', $order->amount)) > 1 ? explode('.', $order->amount)[1] : '00' }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">Предоплата</div>
                    <div class="col-xs-7">
                        <input type="hidden" name="prepayment" value="{{ $order->prepayment }}">
                        <input id="grn2" name="grn2" type="number" size="5"
                               value="{{ explode('.', $order->prepayment)[0] }}">.<input
                                id="kops2" name="kops2" type="number" size="2"
                                value="{{ count(explode('.', $order->prepayment)) > 1 ? explode('.', $order->prepayment)[1] : '00' }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">Доплата</div>
                    <div class="col-xs-7">
                        <input id="grn3" name="grn3" type="number" size="5"
                               value="{{ explode('.', $order->surcharge)[0] }}"
                               disabled>.<input id="kops3" name="kops3" type="number" size="2"
                                                value="{{ count(explode('.', $order->surcharge)) > 1 ? explode('.', $order->surcharge)[1] : '00' }}"
                                                disabled>
                    </div>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="row">
                    <div class="col-xs-4">Дизайн</div>
                    <div class="col-xs-8">
                        <input name="price_design" type="number" size="5"
                               value="{{ $order->price_design }}">
                    </div>
                </div>
            </div>
            <div class="col-xs-7">
                <div class="row">
                    <div class="row">
                        <label><input name="sms1"
                                      type="checkbox" {{ isset($order->sms1) ? 'checked disabled' : '' }}>Отослать
                            sms о приеме заказа</label>
                        @isset($order->sms1) ({{ \App\Models\Sms::getStatus($order->sms1->sms_id) }}) @endisset
                    </div>
                    <div class="row">
                        <label><input name="sms2"
                                      type="checkbox" {{ isset($order->sms2) ? 'checked disabled' : '' }}>Отослать
                            sms о готовности заказа</label>
                        @isset($order->sms2) ({{ \App\Models\Sms::getStatus($order->sms2->sms_id) }}) @endisset
                    </div>
                    <div class="row">
                        <div class="col-xs-5">
                            <input type="submit" class="btn btn-info" value="Сохранить заказ">
                        </div>
                        <div class="col-xs-6">
                            <div id="message"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row row-files">
            <div class="col-xs-4">
                <div class="row">
                    <div class="row js_files">
                        <div class="file-form-wrap">
                            <div class="file-upload">
                                <label>
                                    <input id="uploaded-file1" type="file" name="files[]" multiple onchange="getFileParam();" />
                                    <span>Выберите  или перетащите <br>сюда файлы</span>
                                </label>
                            </div>
                            <div id="preview1">&nbsp;</div>
                            <div id="file-name1">&nbsp;</div>
                            <div id="file-size1">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-7">
                @if($order->is_files)
                    <div class="row">
                        <ol class="file-list">
                            @forelse(Storage::files(\App\Models\Order::FILES_DIR.'/'.$order->id) as $file)
{{--                                {{ dd(pathinfo($file)) }}--}}
                                <li data-delete="{{ route('order.delete_file', ['order' => $order->id, 'file' => pathinfo($file)['basename']]) }}">
                                    <a href="{{ route('order.download_file', ['order' => $order->id, 'file' => pathinfo($file)['basename']]) }}" title="Скачать {{ pathinfo($file)['basename'] }}">{{ pathinfo($file)['basename'] }}</a>
                                    <span class="del-file" title="Удалить {{ pathinfo($file)['basename'] }}">&times;</span>
                                </li>
                            @empty
                            @endforelse
                        </ol>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>
<div class="wrapper"></div>
<script src="{{ asset('js/bootstrap.js') }}" defer></script>
<script src="{{ asset('js/script.js') }}?v_1.11" defer></script>
</body>
</html>