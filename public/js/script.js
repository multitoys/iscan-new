// Зададим стартовую дату
var start = new Date(),
    prevDay,
    startHours = 9;

// 09:00
start.setHours(9);
start.setMinutes(0);

// Если сегодня суббота или воскресенье - 10:00
if ([6, 0].indexOf(start.getDay()) != -1) {
    start.setHours(10);
    startHours = 10
}

$('#timepicker-actions-exmpl').datepicker({
    timepicker: true,
    minDate: new Date(),
    startDate: start,
    minHours: startHours,
    maxHours: 18,
    onSelect: function (fd, d, picker) {
        // Ничего не делаем если выделение было снято
        if (!d) return;

        var day = d.getDay();

        // Обновляем состояние календаря только если была изменена дата
        if (prevDay != undefined && prevDay == day) return;
        prevDay = day;

        // Если выбранный день суббота или воскресенье, то устанавливаем
        // часы для выходных, в противном случае восстанавливаем начальные значения
        if (day == 6 || day == 0) {
            picker.update({
                minHours: 10,
                maxHours: 17
            })
        } else {
            picker.update({
                minHours: 9,
                maxHours: 20
            })
        }
    }
})

function ValidClient() {
    var myClient = document.getElementById('client').value;
    myClient = myClient.replace(/^\s+|\s+$/g, '');
    var valid = myClient ? true : false;
    if (valid) output = '';
    else output = 'Поле ЗАКАЗЧИК - пустое!';
    document.getElementById('message').innerHTML = output + '<br />';
    return valid;
}

function ValidPhone() {
    var re = /^0[\d]{9}$/;
    var myPhone = document.getElementById('phone').value;
    var valid = re.test(myPhone);
    if (valid) output = '';
    else output = 'Номер телефона введен неправильно!';
    document.getElementById('message').innerHTML = document.getElementById('message').innerHTML + output + '<br />';
    return valid;
}

function ValidDate() {
    var myDate = document.getElementById('timepicker-actions-exmpl').value;
    var valid = myDate != '';
    if (valid) output = '';
    else output = 'Не выбрана дата отгрузки заказа!';
    document.getElementById('message').innerHTML = document.getElementById('message').innerHTML + output + '<br />';
    return valid;
}

function ValidOperator() {
    var flag = true;
    $('select[name="operator"]').each(function (i) {
        if (this.value == '') {
            alert('Не выбран орератор!');
            flag = false;
        }
    });
    return flag;
}

function ValidInputNumber() {
    var flag = true;
    $("input#qty").each(function (i) {
        if (parseInt(this.value) < 0) {
            alert('Тираж не может быть меньше 0!');
            flag = false;
        }
    });
    return flag;
}

var grn1 = document.getElementById('grn1');
var grn2 = document.getElementById('grn2');
var grn3 = document.getElementById('grn3');

var kops1 = document.getElementById('kops1');
var kops2 = document.getElementById('kops2');
var kops3 = document.getElementById('kops3');

var amount     = document.getElementById('amount');
var prepayment = document.getElementById('prepayment');

function surcharge(g1, g2, g3, k1, k2, k3, amount, prepayment) {
    g1.value = (g1.value != '')?g1.value:'0';
    g2.value = (g2.value != '')?g2.value:'0';
    k1.value = (k1.value != '')?k1.value:'00';
    k2.value = (k2.value != '')?k2.value:'00';
    var sum = parseInt(g1.value) * 100 + parseInt(k1.value);
    var prepaid = parseInt(g2.value) * 100 + parseInt(k2.value);
    var difference = sum - prepaid;
    if (prepaid == 0) {
        var g3v = parseInt(g1.value);
        var k3v = parseInt(k1.value);
    } else {
        var g3v = parseInt(difference / 100);
        var k3v = parseInt(difference - g3v * 100);
    }
    if ((sum - prepaid) < 0) {
        if (g3v == 0) {
            g3v = '-' + g3v;
        }
    }
    g3.value = g3v;
    g3.setAttribute('value', g3v);
    var kops = Math.abs(k3v);
    kops = kops >= 10 ? kops : "0" + kops;
    k3.value = kops
    k3.setAttribute('value', kops);

    amount.setAttribute('value', sum / 100);
    prepayment.setAttribute('value', prepaid / 100);
}

grn1.onfocus = grn2.onfocus = kops1.onfocus = kops2.onfocus = function (e) {
    this.value = (this.value == '0' || this.value == '00')?'':this.value;
}

grn1.onfocusout = grn2.onfocusout = function (e) {
    this.value = (this.value == '')?'0':this.value;
}

kops1.onfocusout = kops2.onfocusout = function (e) {
    if (this.value == '') {
        this.value = '00';
    } else if (this.value.length == 1) {
        this.value = "0" + this.value;
    } else {
        this.value = this.value;
    }
}

window.onload = grn1.onchange = grn2.onchange = kops1.onchange = kops2.onchange = function () {
    surcharge(grn1, grn2, grn3, kops1, kops2, kops3, amount, prepayment);
}

function throttle(func, ms) {
    var isThrottled = false, savedArgs, savedThis;

    function wrapper() {
        if (isThrottled) {
            savedArgs = arguments;
            savedThis = this;
            return;
        }
        func.apply(this, arguments);
        isThrottled = true;
        setTimeout(function () {
            isThrottled = false;
            if (savedArgs) {
                wrapper.apply(savedThis, savedArgs);
                savedArgs = savedThis = null;
            }
        }, ms);
    }

    return wrapper;
}
$('#search_results, #search_results2, #search_results3').on('click', 'ul li', function () {
    var id     = $(this).attr("data-id");
    var phone  = $(this).attr("data-phone");
    var client = $(this).attr("data-client");
    var email  = $(this).attr("data-email");
    var ready  = parseInt($(this).attr("data-ready"));

    $('[name="client_id"]').attr("value", id);
    $('#phone').attr("value", phone);
    $('#client').attr("value", client);
    $('#email').attr("value", email);

    $("#search_results").html('');
    $("#search_results2").html('');
    $("#search_results3").html('');

    $('#phone').prop("value", phone);
    $('#client').prop("value", client);
    $('#email').prop("value", email);

    $('div.wrapper').css('display', 'none');

    if(ready > 0) {
        alert('У клиента есть незабранный заказ!');
    }
});
$(function () {
    var $liveSearch = $("#search_results"),
        $searchString = $("#phone"),
        $emailSearch = $("#search_results2"),
        $searchEmail = $("#email"),
        $clientSearch = $("#search_results3"),
        $searchClient = $("#client"),
        $wrapper = $('div.wrapper');

    $searchString.keyup(throttle(function () {
        var search = $searchString.val();
        if (search.length > 5) {
            $.ajax({
                type: "get",
                url: $searchString.attr('data-url'),
                data: {search: search, field: 'phone'},
                cache: false,
                success: function (response) {
                    if (response.success) {
                        $wrapper.css('display', 'block');
                        $liveSearch.html(response.content);
                    }
                }
            });
        } else {
            $liveSearch.html('');
            $wrapper.css('display', 'none');
        }
    }, 500));
    $searchEmail.keyup(throttle(function () {
        var search = $searchEmail.val();
        if (search.length > 2) {
            $.ajax({
                type: "get",
                url: $searchEmail.attr('data-url'),
                data: {search: search, field: 'email'},
                cache: false,
                success: function (response) {
                    if (response.success) {
                        $wrapper.css('display', 'block');
                        $emailSearch.html(response.content);
                    }
                }
            });
        } else {
            $emailSearch.html('');
            $wrapper.css('display', 'none');
        }
    }, 500));
    $searchClient.keyup(throttle(function () {
        var search = $searchClient.val();
        if (search.length > 4) {
            $.ajax({
                type: "get",
                url: $searchClient.attr('data-url'),
                data: {search: search, field: 'name'},
                cache: false,
                success: function (response) {
                    if (response.success) {
                        $wrapper.css('display', 'block');
                        $clientSearch.html(response.content);
                    }
                }
            });
        } else {
            $clientSearch.html('');
            $wrapper.css('display', 'none');
        }
    }, 500));

    $wrapper.on('click', function () {
        $liveSearch.html('');
        $emailSearch.html('');
        $clientSearch.html('');
        $(this).css('display', 'none');
    });

    $('span.del-file').on('click', function () {
        var fileLi  = $(this).parents('li');
        var file    = fileLi.find('a').text();
        if (confirm('Удалить файл ' + file)) {
            $.ajax({
                type: "POST",
                url: fileLi.attr('data-delete'),
                data:{
                    _token: $('[name="_token"]').val(),
                    _method: 'DELETE',
                },
                dataType: 'json',
                cache: false,
                success: function (response) {
                    if (response.status == true) {
                        fileLi.remove();
                    } else {
                        alert("Не удалось удалить этот файл!");
                    }
                }
            });
        }
    })
});
function getFileParam() {
    var files = document.getElementById('uploaded-file1').files;
    for (var i = 0; i < files.length; i++) {
        var file = files[i];

        if (file) {
            var fileSize = 0;

            if (file.size > 1024 * 1024) {
                fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
            }else {
                fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
            }

            document.getElementById('file-name1').innerHTML += 'Имя: ' + file.name + '<br>';
            document.getElementById('file-size1').innerHTML += 'Размер: ' + fileSize + '<br>';
        }
    }
}
$(document).ready(function () {
	if ($('[name="status_id"]').val() == 4) {
		$('[name="outsource_id"]').show(100);
	} else {
		$('[name="outsource_id"]').hide();
	}
	$('[name="status_id"]').on('change', function () {
		if ($(this).val() == 4) {
			$('[name="outsource_id"]').show(100);
		} else {
			$('[name="outsource_id"]').hide(100);
		}
	});
});