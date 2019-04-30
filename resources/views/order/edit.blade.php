<!DOCTYPE html>
<html>
<head>
    <title>Заказ</title>
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
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
    <script src="{{ asset('js/datepicker.min.js') }}" defer></script>
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
                <select name="operator" class="form-control">
                    <option value="">Выбор оператора</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : ''}}>{{ $user->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xs-1">Статус заказа</div>
            <div class="col-xs-3">
                <select name="status" class="form-control">
                    @foreach ($statuses as $status)
                    <option value="{{ $status->id }}" {{ $order->status_id == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                    @endforeach
                </select>
                <select name="outsource" class="form-control" style="display:none;">
                    <option value="">Выберите производство</option>
                    @foreach ($outsources as $outsource)
                    <option value="{{ $outsource->id }}" {{ $order->outsource_id == $outsource->id ? 'selected' : ''}}>{{ $outsource->code }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{ dd() }}
        <hr>
        <div class="row">
            <div class="col-xs-1">Заказчик</div>
            <div class="col-xs-3">
                <input name="client" id="client" type="text" class="form-control"
                       value="<?php if (isset($order['client'])): ?><?= $order['client'] ?><?php endif; ?>"
                       autocomplete="off">
                <div id="search_results3"></div>
            </div>
            <div class="col-xs-1">Моб. тел.</div>
            <div class="col-xs-3">
                <div class="input-group">
                    <span class="input-group-addon">38</span>
                    <input name="phone" id="phone" type="tel" class="form-control"
                           value="<?php if (isset($order['phone'])): ?><?= $order['phone'] ?><?php endif; ?>"
                           autocomplete="off">
                    <div id="search_results"></div>
                </div>
            </div>
            <div class="col-xs-1">E-mail</div>
            <div class="col-xs-3">
                <div class="input-group">
                    <span class="input-group-addon">@</span>
                    <input name="email" id="email" type="text" class="form-control"
                           value="<?php if (isset($order['email'])): ?><?= $order['email'] ?><?php endif; ?>"
                           autocomplete="off">
                    <div id="search_results2"></div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-xs-3">Дата принятия заказа</div>
            <div class="col-xs-3"><?php if (isset($order['date_create'])): ?><?= substr($order['date_create'], 0, -3) ?><?php endif; ?></div>
            <div class="col-xs-3">Способ оплаты</div>
            <div class="col-xs-3">
                <select name="pay" class="form-control">
                    <option value="Наличные"<?php if (isset($order['pay']) && $order['pay'] == "Наличные"): ?> selected<?php endif; ?>>
                        Наличные
                    </option>
                    <option value="Счёт б/н"<?php if (isset($order['pay']) && $order['pay'] == "Счёт"): ?> selected<?php endif; ?>>
                        Счёт
                    </option>
                    <option value="Карта"<?php if (isset($order['pay']) && $order['pay'] == "Карта"): ?> selected<?php endif; ?>>
                        Карта
                    </option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">Дата отгрузки заказа</div>
            <div class="col-xs-3">
                <input id="timepicker-actions-exmpl" name="date" type="text" class="form-control"
                       value="<?php if (isset($order['date'])): ?><?= $order['date'] ?><?php endif; ?>">
            </div>
        </div>
        <hr>
        <div class="row submit">
            <div class="col-xs-5">
                <div class="row">
                    <div class="col-xs-4">Выбор услуги</div>
                    <div class="col-xs-8">
                        <select name="service" class="form-control">
                            <?php foreach ($services as $service): ?>
                            <option value="<?= $service ?>"<?php if (isset($order['service']) && $order['service'] == $service): ?> selected<?php endif; ?>><?= $service ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">Тираж</div>
                    <div class="col-xs-8">
                        <input id="qty" name="qty" type="number" class="form-control"
                               value="<?php if (isset($order['qty'])): ?><?= $order['qty'] ?><?php endif; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">Цветность</div>
                    <div class="col-xs-2">
                        <label class="checkbox-inline"><input name="incolor"
                                                              type="checkbox"<?php if (isset($order['incolor'])): ?>

                                                              <?php if ($order['incolor'] == 'on'): ?> checked<?php endif; ?><?php endif; ?>>цвет</label>
                    </div>
                    <div class="col-xs-2">
                        <label class="checkbox-inline"><input name="noncolor"
                                                              type="checkbox"<?php if (isset($order['noncolor'])): ?><?php if ($order['noncolor'] == 'on'): ?> checked<?php endif; ?><?php endif; ?>>чб</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">Тип бумаги</div>
                    <div class="col-xs-8">
                        <select name="paper" class="form-control">
                            <?php foreach ($papers as $paper): ?>
                            <option value="<?= $paper ?>"<?php if (isset($order['paper']) && $order['paper'] == $paper): ?> selected<?php endif; ?>><?= $paper ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-7">
                <div class="row textarea">
                    <div class="col-xs-3">Комментарий к заказу</div>
                    <div class="col-xs-9">
						<textarea name="comment_full" rows="7" class="form-control" placeholder="Комментарий к заказу"
                                  value="<?php if (isset($order['comment_full'])): ?><?= $order['comment_full'] ?><?php endif; ?>"><?php if (isset($order['comment_full'])): ?><?= $order['comment_full'] ?><?php endif; ?></textarea>
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
                        <input id="grn1" name="grn1" type="number" size="5"
                               value="<?php if (isset($order['grn1'])): ?><?= $order['grn1'] ?><?php endif; ?>">.<input
                                id="kops1" name="kops1" type="number" size="2"
                                value="<?php if (isset($order['kops1'])): ?><?= $order['kops1'] ?><?php endif; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">Предоплата</div>
                    <div class="col-xs-7">
                        <input id="grn2" name="grn2" type="number" size="5"
                               value="<?php if (isset($order['grn2'])): ?><?= $order['grn2'] ?><?php endif; ?>">.<input
                                id="kops2" name="kops2" type="number" size="2"
                                value="<?php if (isset($order['kops2'])): ?><?= $order['kops2'] ?><?php endif; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">Доплата</div>
                    <div class="col-xs-7">
                        <input id="grn3" name="grn3" type="number" size="5"
                               value="<?php if (isset($order['grn3'])): ?><?= $order['grn3'] ?><?php endif; ?>"
                               disabled>.<input id="kops3" name="kops3" type="number" size="2"
                                                value="<?php if (isset($order['kops3'])): ?><?= $order['kops3'] ?><?php endif; ?>"
                                                disabled>
                    </div>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="row">
                    <div class="col-xs-4">Дизайн</div>
                    <div class="col-xs-8">
                        <input name="price_design" type="number" size="5"
                               value="<?php if (isset($order['price_design'])): ?><?= $order['price_design'] ?><?php endif; ?>">
                    </div>
                </div>
            </div>
            <div class="col-xs-7">
                <div class="row">
                    <div class="row">
                        <label><input name="sms"
                                      type="checkbox"<?php if (isset($order['sms'])): ?><?php if ($order['sms'] == 'on'): ?> checked disabled<?php endif; ?><?php endif; ?>>Отослать
                            sms о приеме заказа</label> <?= isset($order['sms1_status']) ? '('.$order['sms1_status'].')' : '' ?>
                    </div>
                    <div class="row">
                        <label><input name="sms2"
                                      type="checkbox"<?php if (isset($order['sms2'])): ?><?php if ($order['sms2'] == 'on'): ?> checked disabled<?php endif; ?><?php endif; ?>>Отослать
                            sms о готовности заказа</label> <?= isset($order['sms2_status']) ? '('.$order['sms2_status'].')' : '' ?>
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
                                    <input id="uploaded-file1" type="file" name="file[]" multiple onchange="getFileParam();" />
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
                <?php
                if ($order['files'] > 0) {
                ?>
                <div class="row">
                    <ol class="file-list">
                        <?php
                        foreach ($files as $file) {
                        ?>
                        <li><a href="/?page=files&order=<?=$order['id'];?>&file=<?=$file;?>" title="Скачать <?=$file;?>"><?=$file;?></a><span class="del-file" title="Удалить <?=$file;?>">&times;</span> </li>
                        <?php
                        }
                        ?>
                    </ol>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </form>
</div>
<div class="wrapper"></div>
<script src="{{ asset('js/bootstrap.js') }}" defer></script>
<script src="{{ asset('js/script.js') }}?v_1.11" defer></script>
</body>
</html>