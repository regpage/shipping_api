<?php
# bfa API ver 1.2.6

if (!isset($_GET['mode'])) {
	exit;
}
$mode = $_GET['mode'];
// Nouse please
if (isset($_GET['tn'])) {
	if (empty($_GET['tn'])) {
		echo 'ЗАПОЛНИТЕ ТРЕЭК-НОМЕР ИЛИ УДАЛИТЕ "tn" ИЗ ЗАПРОСА';
		exit;
	}
	$tnone = $_GET['tn'];
} else {
	$tnone = '';
}
// Query by deal only one
if (isset($_GET['deal'])) {
	if (empty($_GET['deal'])) {
		echo 'ЗАПОЛНИТЕ НОМЕР СДЕЛКИ ИЛИ УДАЛИТЕ "deal" ИЗ ЗАПРОСА';
		exit;
	}
	$dealid = $_GET['deal'];
} else {
	$dealid ='';
}

// TEST
//https://reg.new-constellation.ru/testapishipping.php?mode=test  // ТЕСТ-ЗАПРОС С ТЕСТОВЫМИ ФАЙЛАМИ (апитест), на 3 записи, задания не будут созданы!
//https://reg.new-constellation.ru/testapishipping.php?mode  // ПРЕД-ЗАПРОС С РАБОЧИМИ ФАЙЛАМИ, на 3 записи, задания не будут созданы.

// MASTER
//https://reg.new-constellation.ru/apishipping.php?mode=fully  // ПОЛНЫЙ ЗАПРОС
//https://reg.new-constellation.ru/apishipping.php?mode  // ПРЕД-ЗАПРОС С РАБОЧИМИ ФАЙЛАМИ, на 3 записи, задания не будут созданы.

//https://reg.new-constellation.ru/apishipping.php?mode=test  // ТЕСТ-ЗАПРОС С ТЕСТОВЫМИ ФАЙЛАМИ (апитест), на 3 записи, задания не будут созданы!

## BEGIN
// трек номера автоматически можно склеевать
// Что делать с "без трека", наверное создавать задачи
// ДЕЛАТЬ ПОВТОРНЫЕ ЗАПРОСЫ В СЛУЧАЯХ ЕСЛИ СЕРВЕР НЕ ДОСТУПЕН

// РЕФАКТОРИТЬ В ВИДЕ КЛАССОВ

// НЕКОРРЕКТНЫЕ ОТВЕТЫ И ОШИБКИ ПОСТАРАТЬСЯ ПЕРЕЗАПРОСИТЬ И ОБЕ ПОПЫТКИ ЗАНЕСТИ В ЛОГ (ПУСТОЙ ЗАНЕСТИ КАК ПУСТОЙ)

// T R A S H
# END
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
	</head>
	<body>
		<img id="spinner" src="api/download.gif">
		<div id="content"></div>
<script>
	//можно вставить гив с анимаций и расчитать макс время пасчёта, добавить больше данных в  вывод
document.getElementById('content').innerHTML = "СТАРТ<br>ИДЁТ ЗАПРОС И ОБРАБОТКА ОТВЕТА ИЗ СРМ<br>*****************";
var pathGlo;
var mode = '<?php echo $mode; ?>';
var tnone = '<?php echo $tnone; ?>';
// Query by deal only one
var dealid = '<?php echo $dealid; ?>';
var secondvar = [];
var thirdvar = [];

if (mode === 'test') {
	pathGlo = 'apitest';
} else {
	pathGlo = 'api';
}

function toLogFile(message, type) {
  if (!type) {
    type = 'INFO';
  }
	// ЛОГ ВРЕМЕННО ВЫКЛЮЧЕН ИЗ-ЗА ОШИБОК СЕРВЕРА. ТЕСТИРОВАНИЕ.
  //fetch(pathGlo+'/logwriterapi.php?message='+message+'&type='+type);
}
var datenowforlogpre = new Date();
var datenowforlog = datenowforlogpre.toString();
if (dealid) {
	toLogFile('************************************* ПОПЫТКА ЕДИНИЧНОГО ЗАПРОСА. '+datenowforlog+' *************************************');
} else{
	toLogFile('************************************* ПОПЫТКА ПАКЕТНОГО ЗАПРОСА. '+datenowforlog+' *************************************');
}

// END LOG

// BEGIN QUERY FROM CRM
var stages = 419655;
var offset = 0;
var arr = [], arrby = [], arrint = [], arrerr = [], ans = [];
var i = 0;
let temp;
var msg_error_tracknumber = '';
var msg_info_tracknumber = '';
var stringcount = 0;
var emptystring = 0;
var ignor = 0;
var checkonetn;
var pathtofile;
var timewaitpr = 60000;
var timewaitcrm = 90000;
var timewaitfinal = 150000;
// Query by deal only one
if (!dealid) {
	pathtofile = '/v1/crm/get_tn.php?stage=';
} else {
	pathtofile = '/v1/crm/get_deal.php?deal='+dealid+'&stage=';
	var timewaitpr = 2000;
	var timewaitcrm = 4000;
	var timewaitfinal = 5000;
}
while (i < 10) {
  fetch(pathGlo+pathtofile+stages+'&offset='+offset)
  .then((response) => {
    return response.json();
  })
  .then((data) => {
   if (data['result'].length !== 0) {
     console.log(data['result']);
     // Query by deal only one
     if (dealid) {
     	data['result'] = [data['result']];
     }
     stringcount = stringcount + data['result'].length;
     for (var variable in data['result']) {
     	// Query by deal only one
     	if (dealid && (data['result'][variable]['stage_id'] === '419655' || data['result'][variable]['stage_id'] === '963416')) {
   			console.log('SUCCESS stage is ', data['result'][variable]['stage_name']);
   		} else if (dealid) {
   			console.log('ERROR stage is ', data['result'][variable]['stage_name']);
   			return;
   		}
			 checkonetn = true;
			 if (tnone) {
				 // Ищем заданый трек нонер
				 checkonetn = data['result'][variable]['values']['custom']['crm_132909']['value'] === tnone;
			 }
       if (data['result'].hasOwnProperty(variable) && checkonetn) {
         if (data['result'][variable]['values']['custom']['crm_131685']['value'] === 'Почта' && data['result'][variable]['values']['custom']['crm_132909']['value'][0] !== '-' && data['result'][variable]['values']['custom']['crm_132909']['value'][1] !== '-') {
           if (data['result'][variable]['values']['custom']['crm_132909']['value']) {
             var x = data['result'][variable]['values']['custom']['crm_132909']['value'];
             var y = x.split(' ');
						 var z = x.split(','); // два трек-номера в одном отправлении
						 if (z[1]) {
							 ztrack1 = z[0];
							 ztrack2 = z[1];
							 ztrack1 = ztrack1.trim();
							 ztrack2 = ztrack2.trim();
							 if ((ztrack1.length === 14 && ztrack2.length === 14) || (ztrack1[11] == 'R' && ztrack1[12] == 'U'  && ztrack1.length === 13 && ztrack2[11] == 'R' && ztrack2[12] == 'U' && ztrack2.length === 13)) {
								 arr.push ([data['result'][variable]['id'], ztrack1, data['result'][variable]['values']['custom']['crm_131481']['value'], data['result'][variable]['employee_id'], data['result'][variable]['user_id'], data['result'][variable]['client_id'], data['result'][variable]['stage_id'], data['result'][variable]['client_name']]);
								 arr.push ([data['result'][variable]['id'], ztrack2, data['result'][variable]['values']['custom']['crm_131481']['value'], data['result'][variable]['employee_id'], data['result'][variable]['user_id'], data['result'][variable]['client_id'], data['result'][variable]['stage_id'], data['result'][variable]['client_name']]);
							 } else if (ztrack1.length === 13 && ztrack1[11] === 'B' && ztrack1[12] === 'Y' && ztrack2.length === 13 && ztrack2[11] === 'B' && ztrack2[12] === 'Y') {
								 arrby.push ([data['result'][variable]['id'], ztrack1, data['result'][variable]['values']['custom']['crm_131481']['value'], data['result'][variable]['employee_id'], data['result'][variable]['user_id'], data['result'][variable]['client_id'], data['result'][variable]['stage_id']]);
								 arrby.push ([data['result'][variable]['id'], ztrack2, data['result'][variable]['values']['custom']['crm_131481']['value'], data['result'][variable]['employee_id'], data['result'][variable]['user_id'], data['result'][variable]['client_id'], data['result'][variable]['stage_id']]);
							 } else if (ztrack1.length === 13 && ztrack2.length === 13) {
								 arrint.push ([data['result'][variable]['id'], ztrack1, data['result'][variable]['values']['custom']['crm_131481']['value'], data['result'][variable]['employee_id'], data['result'][variable]['user_id'], data['result'][variable]['client_id'], data['result'][variable]['stage_id']]);
								 arrint.push ([data['result'][variable]['id'], ztrack2, data['result'][variable]['values']['custom']['crm_131481']['value'], data['result'][variable]['employee_id'], data['result'][variable]['user_id'], data['result'][variable]['client_id'], data['result'][variable]['stage_id']]);
								 msg_error_tracknumber = 'Отправление id '+ data['result'][variable]['id'] +', от '+ data['result'][variable]['values']['custom']['crm_131481']['value'] +', на имя ' + data['result'][variable]['client_name'] + ', ТРЕК-НОМЕР НЕ ОПОЗНАН, Трек-номера: '+ data['result'][variable]['values']['custom']['crm_132909']['value'];
							 } else {
								 arrerr.push ([data['result'][variable]['id'], ztrack1, data['result'][variable]['values']['custom']['crm_131481']['value'], data['result'][variable]['employee_id'], data['result'][variable]['user_id'], data['result'][variable]['client_id'], data['result'][variable]['stage_id']]);
								 arrerr.push ([data['result'][variable]['id'], ztrack2, data['result'][variable]['values']['custom']['crm_131481']['value'], data['result'][variable]['employee_id'], data['result'][variable]['user_id'], data['result'][variable]['client_id'], data['result'][variable]['stage_id']]);
								 msg_error_tracknumber = 'Отправление id '+ data['result'][variable]['id'] +', от '+ data['result'][variable]['values']['custom']['crm_131481']['value'] +', на имя ' + data['result'][variable]['client_name'] + ', ТРЕК-НОМЕР НЕ ПРОШЁЛ ПРОВЕРКУ, Трек-номер: '+ data['result'][variable]['values']['custom']['crm_132909']['value'];
							 }
						 } else if (!y[1]) {
               if (x.length === 14 || (x.length === 13 && x[11] == 'R' && x[12] == 'U')) {
                 arr.push ([data['result'][variable]['id'], data['result'][variable]['values']['custom']['crm_132909']['value'], data['result'][variable]['values']['custom']['crm_131481']['value'], data['result'][variable]['employee_id'], data['result'][variable]['user_id'], data['result'][variable]['client_id'], data['result'][variable]['stage_id'], data['result'][variable]['client_name']]);
							 } else if (x[12] == 'Y' && x[11] == 'B' && x.length === 13) {
                  arrby.push ([data['result'][variable]['id'], data['result'][variable]['values']['custom']['crm_132909']['value'], data['result'][variable]['values']['custom']['crm_131481']['value'], data['result'][variable]['employee_id'], data['result'][variable]['user_id'], data['result'][variable]['client_id'], data['result'][variable]['stage_id']]);
                } else if (x.length === 13) {
                  arrint.push ([data['result'][variable]['id'], data['result'][variable]['values']['custom']['crm_132909']['value'], data['result'][variable]['values']['custom']['crm_131481']['value'], data['result'][variable]['employee_id'], data['result'][variable]['user_id'], data['result'][variable]['client_id'], data['result'][variable]['stage_id']]);
									msg_error_tracknumber = 'Отправление id '+ data['result'][variable]['id'] +', от '+ data['result'][variable]['values']['custom']['crm_131481']['value'] +', на имя ' + data['result'][variable]['client_name'] + ', ТРЕК-НОМЕР НЕ ОПОЗНАН, Трек-номер: '+ data['result'][variable]['values']['custom']['crm_132909']['value'];
                } else {
                  arrerr.push ([data['result'][variable]['id'], data['result'][variable]['values']['custom']['crm_132909']['value'], data['result'][variable]['values']['custom']['crm_131481']['value'], data['result'][variable]['employee_id'], data['result'][variable]['user_id'], data['result'][variable]['client_id'], data['result'][variable]['stage_id']]);
									msg_error_tracknumber = 'Отправление id '+ data['result'][variable]['id'] +', от '+ data['result'][variable]['values']['custom']['crm_131481']['value'] +', на имя ' + data['result'][variable]['client_name'] + ', ТРЕК-НОМЕР НЕ ПРОШЁЛ ПРОВЕРКУ, Трек-номер: '+ data['result'][variable]['values']['custom']['crm_132909']['value'];
                }
            } else {
                msg_error_tracknumber = 'Отправление id '+ data['result'][variable]['id'] +', от '+ data['result'][variable]['values']['custom']['crm_131481']['value'] +', на имя ' + data['result'][variable]['client_name'] + ', ТРЕК-НОМЕР ОТСУТСТВУЕТ или С ПРОБЕЛАМИ, Трек-номер: '+ data['result'][variable]['values']['custom']['crm_132909']['value'];
            }
          } else {
            msg_error_tracknumber = 'Отправление id '+ data['result'][variable]['id'] +', от '+ data['result'][variable]['values']['custom']['crm_131481']['value'] +', на имя ' + data['result'][variable]['client_name'] + ', НЕ УКАЗАН ТРЕК-НОМЕР, Трек-номер: '+ data['result'][variable]['values']['custom']['crm_132909']['value'];
          }
        } else {
					let msgdtl1 = 'NONE';
					let msgdtl2 = 'NONE';
					ignor++;
					if (data['result'][variable]['values']['custom']['crm_131481']) {
						msgdtl1 = data['result'][variable]['values']['custom']['crm_131481']['value'];
					}
					if (data['result'][variable]['values']['custom']['crm_132909']) {
						msgdtl2 = data['result'][variable]['values']['custom']['crm_132909']['value'];
					}
          msg_info_tracknumber = 'Отправление id '+ data['result'][variable]['id'] +', от '+ msgdtl1 +', на имя ' + data['result'][variable]['client_name'] + ', ИСКЛЮЧЕНО из запроса (отфильтровано), Трек-номер: '+ msgdtl2;
					if (data['result'][variable]['values']['custom']['crm_132909']) {
						if (data['result'][variable]['values']['custom']['crm_132909']['value'][0] !== '-' && data['result'][variable]['values']['custom']['crm_132909']['value'][1] !== '-') {
							if (mode === 'fully') {
								fetch(pathGlo+'/mailsender.php?msg=Отправление id '+ data['result'][variable]['id'] +', трек-номер: '+ msgdtl2+' от '+ msgdtl1 +', на имя ' + data['result'][variable]['client_name'] + ' ИСКЛЮЧЕНО из запроса НЕ ПО ПРИЗНАКУ "--". Требует выяснения.&debug');
							}
						}
					} else {
						if (mode === 'fully') {
							fetch(pathGlo+'/mailsender.php?msg=Отправление id '+ data['result'][variable]['id'] +', трек-номер: '+ msgdtl2+' от '+ msgdtl1 +', на имя ' + data['result'][variable]['client_name'] + ' ИСКЛЮЧЕНО из запроса, отсутствует трек-номер. Возможно вручается лично или требует выяснения.&debug');
						}
					}
        }
       } else {
         emptystring++;
       }
       if (msg_error_tracknumber) {
         // log
         toLogFile(msg_error_tracknumber, 'WARNING');
         //Report by emailing
         //fetch(pathGlo+'/mailsender.php?msg='+msg_error_tracknumber);
         msg_error_tracknumber = '';
       }
       if (msg_info_tracknumber) {
         // log
         toLogFile(msg_info_tracknumber, 'INFO');
         msg_info_tracknumber = '';
       }
     }
   }
 });
  i++;
  // Query by deal only one
  if (dealid) {
  	i = 10;
  }
  offset = offset + 50;
  if (stages === 419655 && i === 5) {
    stages = 963416;
    offset = 0;
  }
}
// END QUERY FROM CRM

// BEGIN QUERY FROM RUSSIA POST
setTimeout(function () {
	document.getElementById('content').innerHTML = "СТАРТ<br>ИДЁТ ЗАПРОС И ОБРАБОТКА ОТВЕТА ИЗ СРМ<br>*****************<br>ПРОДОЛЖЕНИЕ<br>ИДЁТ ЗАПРОС И ОБРАБОТКА ОТВЕТА ИЗ ПОЧТЫ РОССИИ<br>*****************";
  var count, path, arrlength, nf1;

  toLogFile('Всего записей предоставленно CRM '+stringcount, 'INFO');
  toLogFile('Предоставлено пустых элементов массива из CRM '+emptystring, 'INFO');
  toLogFile('Отфильтровано и искючено из запросов записей полученных из CRM '+ignor, 'INFO');
  toLogFile('Записей для запроса в почту России отобранно '+arr.length, 'INFO');
  toLogFile('Записей для запроса в Белпочту отобранно '+arrby.length, 'INFO');
  toLogFile('Записей международных и прочих отобранно '+arrint.length, 'INFO');
  toLogFile('Ошибок и прочих отфильтровано '+arrerr.length, 'INFO');

  // Почта России
  console.log(arr);
  // ДЛЯ ЗАПРОСОВ НА ТРЕК24 Беларусь, международные и т.п.
  console.log(arrby);
  // международные
  console.log(arrint);
  // остальные и баги
  console.log(arrerr);

	if (mode === 'fully') {
		if (!tnone && !dealid) {
			nf1 = 1;
		} else {
			nf1 = 5;
		}
		arrlength = arr.length;
	} else { //mode mode === 'test'
		arrlength = 3;
		nf1 = 5;
	}

// RUSSIA POST
  for (var j = 0; j < arrlength;  j++) {
    if (j < 100) {
			nf = nf1;
			//nf = 1;
    } else if (j >= 100 && j < 200) {
      nf = 2;
    } else if (j >= 200 && j < 300) {
      nf = 3;
    } else if (j >= 300 && j < 400) {
      nf = 4;
    } else if (j >= 400 && j < 500) {
      nf = 5;
    }

    path = pathGlo+'/v1/pr/pr_client.php?tn='+arr[j][1]+'&id='+arr[j][0]+'&date='+arr[j][2]+'&empl='+arr[j][3]+'&user='+arr[j][4]+'&client='+arr[j][5]+'&stage='+arr[j][6]+'&fio='+arr[j][7]+'&nf='+nf;
    fetch(path)
    .then((response) => {
    	console.log(response.status);
    	if (response.status !== 200) {
    		secondvar.push(response.url.slice(0, -1));
    	}
      return response.text();
    })
    .then((data) => {
    	//console.log(data);
      ans.push(data);
    });
  }
}, timewaitpr);

// END QUERY FROM RUSSIA POST

// BEGIN SECOND QUERY FROM CRM

setTimeout(function () {
	document.getElementById('content').innerHTML = "СТАРТ<br>ИДЁТ ЗАПРОС И ОБРАБОТКА ОТВЕТА ИЗ СРМ<br>*****************<br>ПРОДОЛЖЕНИЕ<br>ИДЁТ ЗАПРОС И ОБРАБОТКА ОТВЕТА ИЗ ПОЧТЫ РОССИИ<br>*****************<br>ИДЁТ ЗАПРОС В СРМ<br>*****************";
	var warningmessages = "";
	// date
	var datetoday;
	var dateprepare;
	var dateorder;
	var resdate;
  for (var string in ans) {
// prepare array
    var arr_str = ans[string].split('-o-o->');
    console.log(arr_str);
// Проверка здесь
    if (arr_str[0] !== 'no any answer' && ans[string][0] !== '<' && ans[string][1] !== 'h' && ans[string][4] !== '>' && ans[string][1] !== 'b') {

// date
      datetoday = new Date();
      if (arr_str[2]) {
        dateprepare = arr_str[2].split('.');
        dateorder = new Date(dateprepare[2],dateprepare[1]-1,dateprepare[0]);
        resdate = (datetoday - dateorder) / (1000 * 3600 * 24);
			} else {
				resdate = 500;
				dateorder = 0;
			}

// date of last record
			var lastdateprepare;
			var lastdateorder;
			if (arr_str[arr_str.length-3]) {
				lastdateorder = new Date(arr_str[arr_str.length-3]);
			} else {
				lastdateorder = 0;
			}
			var lastresdate = (datetoday - lastdateorder) / (1000 * 3600 * 24);

// rules
      if (!arr_str[arr_str.length-1] && resdate <= 3) {
				// log
				//toLogFile('Отправление по сделке № '+arr_str[0]+' отправлено 3 или МЕНЕЕ дней назад, нет данных о движении по треку от почты', 'INFO');
			} else if (!arr_str[arr_str.length-1] && resdate > 3) {
        // нет данных больше трех дней по трек-номеру. Оповещение админа, (учитывать дату отправки) и переход на ручной контроль.
				// log
				toLogFile('отправление по сделке № '+arr_str[0]+' отправлено БОЛЕЕ 3-х дней назад, но нет данных о движении по треку от почты', 'WARNING');
				// emailing
				//fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' отправлено БОЛЕЕ 3-х дней назад, но нет данных о движении по треку от почты');
      } else if (arr_str[arr_str.length-1] === 'Единичный' || arr_str[arr_str.length-1] === 'Партионный' || arr_str[arr_str.length-1] === 'Упрощенный предоплаченный') {
				// log
				toLogFile('Отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' принято к отправлению '+Math.round(resdate)+' дней назад.');
      } else if (arr_str[arr_str.length-1] === 'Прибыло в место вручения' || arr_str[arr_str.length-1] === 'Временное отсутствие адресата' || arr_str[arr_str.length-1] === 'Адресат заберет отправление сам' || arr_str[arr_str.length-1] === 'Иная' || arr_str[arr_str.length-1] ===  'Неудачная доставка' || arr_str[arr_str.length-1] ===  'Адресат не доступен') {
        if (resdate < 51 && arr_str[6] == '419655' && mode === 'fully') {
          // task
          fetch(pathGlo+'/v1/crm/set_task.php?deal='+arr_str[0]+'&time=64800&comm=ОЖИДАЕТ ВРУЧЕНИЯ С '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]);
					// history
          fetch(pathGlo+'/v1/crm/set_deal_log.php?deal='+arr_str[0]+'&comment=ОЖИДАЕТ ВРУЧЕНИЯ С '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]);
          // stage
          fetch(pathGlo+'/v1/crm/set_stage.php?deal='+arr_str[0]+'&stage=963416');
        } else if (resdate >= 51  && arr_str[6] == '419655' && mode === 'fully'){
          // task
          fetch(pathGlo+'/v1/crm/set_task.php?deal='+arr_str[0]+'&time=64800&mark=2&comm=ВОЗВРАТ ОЖИДАЕТ С '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]);
					// history
          fetch(pathGlo+'/v1/crm/set_deal_log.php?deal='+arr_str[0]+'&mark=2&comment=ВОЗВРАТ ОЖИДАЕТ С '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]);
          // stage
          fetch(pathGlo+'/v1/crm/set_stage.php?deal='+arr_str[0]+'&stage=963416');
          //emailing
          //fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' прибыло, но со дня отправки прошло 50 или БОЛЕЕ дней');
					// log
          toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' прибыло, но со дня отправки прошло 50 или БОЛЕЕ дней', 'WARNING');
        } else {
					if (lastresdate >= 20) {
						if (mode === 'fully') {
							warningmessages += arr_str[7]+', заказ '+arr_str[0]+', трек-номер '+arr_str[1]+', со дня прибытия бандероли прошло 20 или более дней <br>';
							//fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' прибыло в почтовое отделение, но со дня прибытия прошло 20 или более дней');
						}
						//toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' прибыло в почтовое отделение, но со дня прибытия прошло 20 или более дней', 'WARNING');
					}
        }
        console.log(arr_str[arr_str.length-1]);
      } else if (arr_str[arr_str.length-1] === 'Вручение адресату' || arr_str[arr_str.length-1] === 'Адресату почтальоном' || arr_str[arr_str.length-1] === 'Адресату с контролем ответа') {
        if (resdate > 51) {
						if (mode === 'fully') {
							// task
		          fetch(pathGlo+'/v1/crm/set_task.php?deal='+arr_str[0]+'&time=64800&comm=ВОЗВРАТ ПОЛУЧЕН '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]+'&mark=2');
							// history
							fetch(pathGlo+'/v1/crm/set_deal_log.php?deal='+arr_str[0]+'&comment=ВОЗВРАТ ПОЛУЧЕН '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]+'&mark=2');
		          //emailing
		          //fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' было вручено, но со дня отправки прошло 50 или более дней');
						}
						// log
						toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' было вручено, но со дня отправки прошло 50 или БОЛЕЕ дней', 'INFO');
        } else if (resdate <= 51) {
						if (mode === 'fully') {
							// task
							fetch(pathGlo+'/v1/crm/set_task.php?deal='+arr_str[0]+'&time=64800&comm=ПОЛУЧЕНО АДРЕСАТОМ '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]+'&mark=1');
							// history
							fetch(pathGlo+'/v1/crm/set_deal_log.php?deal='+arr_str[0]+'&comment=ПОЛУЧЕНО АДРЕСАТОМ '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]+'&mark=1');
							// log
							//toLogFile('Отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' ВРУЧЕНО, и ОБРАБОТАНО.');
						}
        }
        if (arr_str[6] == '419655' && mode === 'fully') {
          // stage
          fetch(pathGlo+'/v1/crm/set_stage.php?deal='+arr_str[0]+'&stage=963416');
        }
        console.log(arr_str[arr_str.length-1]);
			} else if (arr_str[arr_str.length-1] === 'Невостребовано' || arr_str[arr_str.length-1] === 'Передача на временное хранение' || arr_str[arr_str.length-1] === 'Истек срок хранения' || arr_str[arr_str.length-1] === 'Покинуло место возврата/досылки' || arr_str[arr_str.length-1] === 'Иные обстоятельства' || arr_str[arr_str.length-1] === 'Иная' || arr_str[arr_str.length-1] === 'Невозможно прочесть адрес адресата') {
        if (arr_str[6] == '963416' && mode === 'fully') {
          // task
          fetch(pathGlo+'/v1/crm/set_task.php?deal='+arr_str[0]+'&mark=2&time=928800&comm=ОТПРАВЛЕНИЕ ВОЗВРАЩАЕТСЯ '+arr_str[arr_str.length-3].substr(0,10));
					// history
          fetch(pathGlo+'/v1/crm/set_deal_log.php?deal='+arr_str[0]+'&mark=2&comment=ОТПРАВЛЕНИЕ ВОЗВРАЩАЕТСЯ '+arr_str[arr_str.length-3].substr(0,10));
          // stage
          fetch(pathGlo+'/v1/crm/set_stage.php?deal='+arr_str[0]+'&stage=419655');
        }
        if (resdate > 59) {
          //emailing
          fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' невостребовано, со дня отправки прошло '+Math.round(resdate)+' дней. Последнее движение было '+arr_str[arr_str.length-3]+'.');
					fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' невостребовано, со дня отправки прошло '+Math.round(resdate)+' дней. Последнее движение было '+arr_str[arr_str.length-3]+'.');
					// log
          toLogFile('Отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' невостребовано, со дня отправки прошло '+Math.round(resdate)+' дней. Последнее движение было '+arr_str[arr_str.length-3]+'.', 'WARNING');
        }
        console.log(arr_str[arr_str.length-1]);
      } else if (arr_str[arr_str.length-1] === 'Прибыло в сортировочный центр' || arr_str[arr_str.length-1] === 'Сортировка' || arr_str[arr_str.length-1] === 'Покинуло сортировочный центр' || arr_str[arr_str.length-1] === 'Покинуло место приёма' || arr_str[arr_str.length-1] === 'Покинуло место международного обмена' || arr_str[arr_str.length-1] ===  'Упрощенный предзаполненный') {
        // task
        /*if (resdate < 10) {
          var z = (10 - Math.round(resdate)) * 24 * 60 * 60+(64800);
        } else {
          z = 64800;
        }*/
				// task
        //fetch(pathGlo+'/v1/crm/set_task.php?deal='+arr_str[0]+'&time='+z+'&comm=Отправление находится в пути');
        if (resdate > 30) {
          //emailing
          //fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' в пути, но со дня отправки прошло '+Math.round(resdate)+' дней');
					// log
					toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' в пути, но со дня отправки прошло '+Math.round(resdate)+' дней', 'WARNING');
        } else if (resdate > 59) {
					//emailing
          fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' в пути, но со дня отправки прошло '+Math.round(resdate)+' дней. Последнее движение было '+arr_str[arr_str.length-3]+'.');
					// log
					toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' в пути, но со дня отправки прошло '+Math.round(resdate)+' дней. Последнее движение было '+arr_str[arr_str.length-3]+'.', 'WARNING');
        }
        console.log(arr_str[arr_str.length-1]);
      } else if (arr_str[arr_str.length-1] === 'Засылка') {
				toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' ПЕРЕНАПРАВЛЕНО НА ВЕРНЫЙ АДРЕС. Со дня отправки прошло '+Math.round(resdate)+' дней', 'WARNING');
				// emailing
	      fetch(pathGlo+'/mailsender.php?msg=Отправление по сделке № '+arr_str[1]+' с трек-номером '+arr_str[2]+'. ПЕРЕНАПРАВЛЕНО НА ВЕРНЫЙ АДРЕС. ВОЗМОЖНО ЭТО ВОЗВРАТ. Со дня отправки прошло '+Math.round(resdate)+' дней. ОТЛАДОЧНАЯ ИНФОРМАЦИЯ.&debug');
      } else {
				// emailing
        //fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+'. Ответ от почты: '+arr_str[arr_str.length-1]+', не определён в системе, возможно его нет в нашем списке');
				// log
				toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+'. Ответ от почты: '+arr_str[arr_str.length-1]+', не определён в системе, возможно его нет в нашем списке', 'ERROR');
        console.log(arr_str[arr_str.length-1]);
      }
    } else if (arr_str[0] === 'no any answer') {
			// date
		    datetoday = new Date();
		    if (arr_str[3]) {
		      dateprepare = arr_str[3].split('.');
					dateorder = new Date(dateprepare[2],dateprepare[1]-1,dateprepare[0]);
	 		    resdate = (datetoday - dateorder) / (1000 * 3600 * 24);
				} else {
					resdate = -1;
				}

			if (resdate > 3) {
				if (mode === 'fully') {
					// emailing
	      	fetch(pathGlo+'/mailsender.php?msg=Отправление по сделке № '+arr_str[1]+' с трек-номером '+arr_str[2]+'. С сервера почты не поступило никакого ответа ('+arr_str[0]+'). ОТПРАВЛЕНИЕ ОТПРАВЛЕНО БОЛЕЕ 3-х ДНЕЙ НАЗАД ВОЗМОЖНО ПРОБЛЕМА С ТРЕК НОМЕРОМ ИЛИ ТРЕК ОТСУТСТВУЕТ В БАЗЕ ПОЧТЫ РОССИИ, ТАК ЖЕ, ВОЗМОЖНО, ПОЧТОВАЯ СЛУЖБА НЕ ОПРЕДЕЛЕНА В НАШЕЙ СИСТЕМЕ.');
				}
				// log
				toLogFile('отправление по сделке № '+arr_str[1]+' с трек-номером '+arr_str[2]+'. Не поступило никакого ответа ('+arr_str[0]+'). ОТПРАВЛЕНИЕ ОТПРАВЛЕНО БОЛЕЕ 3-х ДНЕЙ НАЗАД. ВОЗМОЖНО ПРОБЛЕМА С ТРЕК НОМЕРОМ ИЛИ ТРЕК ОТСУТСТВУЕТ В БАЗЕ ПОЧТЫ РОССИИ, ТАК ЖЕ, ВОЗМОЖНО, ПОЧТОВАЯ СЛУЖБА НЕ ОПРЕДЕЛЕНА В НАШЕЙ СИСТЕМЕ.', 'ERROR');
			} else if (resdate === -1) {
				var dopstr = 'NONE';
				if (!Array.isArray(arr_str)) {
					dopstr = arr_str;
				}
				// log
				toLogFile('отправление по сделке № '+arr_str[1]+' с трек-номером '+arr_str[2]+'. Не поступило ответа ('+arr_str[0]+'). ДАТА ОТСУТСТВУЕТ. ВОЗМОЖНО ПРОБЛЕМА С ТРЕК НОМЕРОМ. ДОП. ДАННЫЕ '+dopstr, 'FATAL');
			} else {
				// log
				toLogFile('отправление по сделке № '+arr_str[1]+' с трек-номером '+arr_str[2]+'. Не поступило никакого ответа ('+arr_str[0]+'). ОТПРАВЛЕНИЕ ОТПРАВЛЕНО 3 или МЕНЕЕ 3-х ДНЕЙ НАЗАД. ВОЗМОЖНО ПРОБЛЕМА С ТРЕК НОМЕРОМ ИЛИ ТРЕК ОТСУТСТВУЕТ В БАЗЕ ПОЧТЫ РОССИИ, ТАК ЖЕ, ВОЗМОЖНО, ПОЧТОВАЯ СЛУЖБА НЕ ОПРЕДЕЛЕНА В НАШЕЙ СИСТЕМЕ.', 'WARNING');
			}
    } else {
    	if (ans[string][1] === 'b') {
    		// log
				toLogFile('СЕРВЕР ПОЧТЫ РОССИИ ВЕРНУЛ ОШИБКУ. ВОЗМОЖНО ПРОБЛЕМА С АВТОРИЗАЦИЕЙ ИЛИ ПРЕВЫШЕН ЛИМИТ. ТРЕБУЕТ УТОЧНЕНИЯ', 'FATAL');
    	} else {
				// log
				toLogFile('СЕРВЕР ПОЧТЫ РОССИИ ВЕРНУЛ ОШИБКУ. ВОЗМОЖНО ОШИБКА СЕРВЕРА.', 'FATAL');
    	}
    }
  }
  // emailing for manager (post)
  if (warningmessages) {
  	fetch(pathGlo+'/mailsenderpost.php?msg&type=post', {
  		method: 'POST',
  		body: warningmessages
  	});
  }
  console.log(ans);
  console.log(secondvar);
}, timewaitcrm);
// END SECOND QUERY TO CRM

// TRACK24
// BELARUS POST

if (mode === 'fully') {
	var ansby = [];
setTimeout(function () {
  for (var jj = 0; jj < arrby.length;  jj++) {
    ansby.push([arrby[jj][0],arrby[jj][1],arrby[jj][2],arrby[jj][3],arrby[jj][4],arrby[jj][5],arrby[jj][6]]);
    pathby = pathGlo+'/v1/track24/client_tr24.php?tn='+arrby[jj][1];
    fetch(pathby)
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      ansby.push(data);
    });
  }
}, 65000);


setTimeout(function () {
	console.log(ansby);
  for (var jjby = 0; jjby < ansby.length;  jjby=jjby+2) {
		// emailing ВРЕМЕНННЫЙ
		fetch(pathGlo+'/mailsender.php?msg=Отправление по сделке № '+ansby[jjby][0]+' с трек-номером '+ansby[jjby][1]+' ОТПРАВЛЕНО ИЗ БЕЛОРУСИ. ОТЛАДОЧНАЯ ИНФОРМАЦИЯ.&debug');
		toLogFile('TRACK24. Отправление по сделке № '+ansby[jjby][0]+' с трек-номером '+ansby[jjby][1]+' ОТПРАВЛЕНО ИЗ БЕЛОРУСИИ. ОТЛАДОЧНАЯ ИНФОРМАЦИЯ.', 'FATAL');
    if (ansby[jjby+1]['data']) {
      if (ansby[jjby+1]['data']['lastPoint']['operation'] === 'сортировка') {
				console.log('БЕЛАРУСЬ ', ansby[jjby+1]['data']['lastPoint']['operation']);
      } else if (ansby[jjby+1]['data']['lastPoint']['operation'] === 'Срок хранения истек. Выслано обратно отправителю') {
				console.log('БЕЛАРУСЬ ', ansby[jjby+1]['data']['lastPoint']['operation']);
      } else if (ansby[jjby+1]['data']['lastPoint']['operation'] === 'Неудачная попытка вручения' || ansby[jjby+1]['data']['lastPoint']['operation'] === 'Ожидает адресата в месте вручения') {
        console.log('БЕЛАРУСЬ ', ansby[jjby+1]['data']['lastPoint']['operation']);
      } else {
      	console.log('БЕЛАРУСЬ ', ansby[jjby+1]['data']['lastPoint']['operation']);
      }
    }
  }
}, 95000);
}
/**/

/*// SAVE ANSWER FROM RUSSIAN POST
setTimeout(function () {
	cashpr = [arr,ans,arrerr];
	for (var iii = 0; iii < 3; iii++) {
		fetch('api/service/saveanswer.php?key=PR', {
  		method: 'POST',
  		headers: {
    	'Content-Type': 'application/json;charset=utf-8'
  		},
  		body: JSON.stringify(cashpr[iii])
		});
	}
}, 37000);
*/
var secondtimewaitcrm = 120000;
var secondquerytimewaiting = 100000;
var anssecond = [];
// BEGIN SECOND QUERY FROM RUSSIA POST
setTimeout(function () {

  document.getElementById('content').innerHTML = "СТАРТ<br>ИДЁТ ЗАПРОС И ОБРАБОТКА ОТВЕТА ИЗ СРМ<br>*****************<br>ПРОДОЛЖЕНИЕ<br>ИДЁТ ПОВТОРНЫЙ ЗАПРОС И ОБРАБОТКА ОТВЕТА ИЗ ПОЧТЫ РОССИИ<br>*****************";
  toLogFile('Всего предоставленно CRM записей не прошло'+secondvar.length, 'WARNING');

  var count, path, arrlength, nf;

// RUSSIA POST
  for (var jjj = 0; jjj < secondvar.length;  jjj++) {
    if (jjj < 100) {
			nf = 4;
    } else if (jjj >= 100 && jjj < 200) {
      nf = 3;
    } else if (jjj >= 200 && jjj < 300) {
      nf = 2;
    } else if (jjj >= 300 && jjj < 400) {
      nf = 1;
    }

    path = secondvar[jjj] + nf;
    fetch(path)
    .then((response) => {
      return response.text();
    })
    .then((data) => {
    	if (data[0] === '<') {
				thirdvar.push(data)
    	}
      anssecond.push(data);
    });
  }
}, secondquerytimewaiting);

// END QUERY FROM RUSSIA POST

// BEGIN THIRD QUERY FROM CRM

setTimeout(function () {

  document.getElementById('content').innerHTML = "СТАРТ<br>ИДЁТ ДОПОЛНИТЕЛЬНЫЙ ЗАПРОС И ОБРАБОТКА ОТВЕТА ИЗ СРМ<br>*****************<br>ПРОДОЛЖЕНИЕ<br>ИДЁТ ЗАПРОС И ОБРАБОТКА ДОПОЛНИТЕЛЬНОГО ЗАПРОСА И ОТВЕТА ОТ ПОЧТЫ РОССИИ<br>*****************<br>ИДЁТ ЗАПРОС В СРМ<br>*****************";
  var warningmessages2="";
   // date
  var datetoday;
  var dateprepare;
  var dateorder;
  var resdate;
  for (var string in anssecond) {
// prepare array
    var arr_str = anssecond[string].split('-o-o->');
    console.log(arr_str);
// Проверка здесь
    if (arr_str[0] !== 'no any answer' && anssecond[string][0] !== '<' && anssecond[string][1] !== 'h' && anssecond[string][4] !== '>' && anssecond[string][1] !== 'b') {

// date
      datetoday = new Date();
      if (arr_str[2]) {
        dateprepare = arr_str[2].split('.');
        dateorder = new Date(dateprepare[2],dateprepare[1]-1,dateprepare[0]);
        resdate = (datetoday - dateorder) / (1000 * 3600 * 24);
      } else {
        resdate = 500;
        dateorder = 0;
      }

// date of last record
      var lastdateprepare;
      var lastdateorder;
      if (arr_str[arr_str.length-3]) {
        lastdateorder = new Date(arr_str[arr_str.length-3]);
      } else {
        lastdateorder = 0;
      }
      var lastresdate = (datetoday - lastdateorder) / (1000 * 3600 * 24);

// rules
      if (!arr_str[arr_str.length-1] && resdate <= 3) {
        // log
        toLogFile('Отправление по сделке № '+arr_str[0]+' отправлено 3 или МЕНЕЕ дней назад, нет данных о движении по треку от почты', 'INFO');
      } else if (!arr_str[arr_str.length-1] && resdate > 3) {
        // нет данных больше трех дней по трек-номеру. Оповещение админа, (учитывать дату отправки) и переход на ручной контроль.
        // log
        toLogFile('отправление по сделке № '+arr_str[0]+' отправлено БОЛЕЕ 3-х дней назад, но нет данных о движении по треку от почты', 'WARNING');
        // emailing
        //fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' отправлено БОЛЕЕ 3-х дней назад, но нет данных о движении по треку от почты');
      } else if (arr_str[arr_str.length-1] === 'Единичный' || arr_str[arr_str.length-1] === 'Партионный' || arr_str[arr_str.length-1] === 'Упрощенный предоплаченный') {
        // log
        toLogFile('Отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' принято к отправлению '+Math.round(resdate)+' дней назад.');
      } else if (arr_str[arr_str.length-1] === 'Прибыло в место вручения' || arr_str[arr_str.length-1] === 'Временное отсутствие адресата' || arr_str[arr_str.length-1] === 'Адресат заберет отправление сам' || arr_str[arr_str.length-1] === 'Иная' || arr_str[arr_str.length-1] ===  'Неудачная доставка' || arr_str[arr_str.length-1] ===  'Адресат не доступен') {
        if (resdate < 51 && arr_str[6] == '419655' && mode === 'fully') {
          // task
          fetch(pathGlo+'/v1/crm/set_task.php?deal='+arr_str[0]+'&time=64800&comm=ОЖИДАЕТ ВРУЧЕНИЯ С '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]);
          // history
          fetch(pathGlo+'/v1/crm/set_deal_log.php?deal='+arr_str[0]+'&comment=ОЖИДАЕТ ВРУЧЕНИЯ С '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]);
          // stage
          fetch(pathGlo+'/v1/crm/set_stage.php?deal='+arr_str[0]+'&stage=963416');
        } else if (resdate >= 51  && arr_str[6] == '419655' && mode === 'fully'){
          // task
          fetch(pathGlo+'/v1/crm/set_task.php?deal='+arr_str[0]+'&time=64800&mark=2&comm=ВОЗВРАТ ОЖИДАЕТ С '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]);
          // history
          fetch(pathGlo+'/v1/crm/set_deal_log.php?deal='+arr_str[0]+'&mark=2&comment=ВОЗВРАТ ОЖИДАЕТ С '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]);
          // stage
          fetch(pathGlo+'/v1/crm/set_stage.php?deal='+arr_str[0]+'&stage=963416');
          //emailing
          //fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' прибыло, но со дня отправки прошло 50 или БОЛЕЕ дней');
          // log
          toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' прибыло, но со дня отправки прошло 50 или БОЛЕЕ дней', 'WARNING');
        } else {
          if (lastresdate >= 20) {
            if (mode === 'fully') {
            	warningmessages2 += arr_str[7]+', заказ '+arr_str[0]+', трек-номер '+arr_str[1]+', со дня прибытия бандероли прошло 20 или более дней <br>';
              //fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' прибыло в почтовое отделение, но со дня прибытия прошло 20 или более дней');
            }
            //toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' прибыло в почтовое отделение, но со дня прибытия прошло 20 или более дней', 'WARNING');
          }
        }
        console.log(arr_str[arr_str.length-1]);
      } else if (arr_str[arr_str.length-1] === 'Вручение адресату' || arr_str[arr_str.length-1] === 'Адресату почтальоном' || arr_str[arr_str.length-1] === 'Адресату с контролем ответа') {
        if (resdate > 51) {
            if (mode === 'fully') {
              // task
              fetch(pathGlo+'/v1/crm/set_task.php?deal='+arr_str[0]+'&time=64800&comm=ВОЗВРАТ ПОЛУЧЕН '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]+'&mark=2');
              // history
              fetch(pathGlo+'/v1/crm/set_deal_log.php?deal='+arr_str[0]+'&comment=ВОЗВРАТ ПОЛУЧЕН '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]+'&mark=2');
              //emailing
              //fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' было вручено, но со дня отправки прошло 50 или более дней');
            }
            // log
            toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' было вручено, но со дня отправки прошло 50 или БОЛЕЕ дней', 'INFO');
        } else if (resdate <= 51) {
            if (mode === 'fully') {
              // task
              fetch(pathGlo+'/v1/crm/set_task.php?deal='+arr_str[0]+'&time=64800&comm=ПОЛУЧЕНО АДРЕСАТОМ '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]+'&mark=1');
              // history
              fetch(pathGlo+'/v1/crm/set_deal_log.php?deal='+arr_str[0]+'&comment=ПОЛУЧЕНО АДРЕСАТОМ '+arr_str[arr_str.length-3].substr(0,10)+': '+ arr_str[arr_str.length-2]+'&mark=1');
              // log
              //toLogFile('Отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' ВРУЧЕНО, и ОБРАБОТАНО.');
            }
        }
        if (arr_str[6] == '419655' && mode === 'fully') {
          // stage
          fetch(pathGlo+'/v1/crm/set_stage.php?deal='+arr_str[0]+'&stage=963416');
        }
        console.log(arr_str[arr_str.length-1]);
      } else if (arr_str[arr_str.length-1] === 'Невостребовано' || arr_str[arr_str.length-1] === 'Передача на временное хранение' || arr_str[arr_str.length-1] === 'Истек срок хранения' || arr_str[arr_str.length-1] === 'Покинуло место возврата/досылки' || arr_str[arr_str.length-1] === 'Иные обстоятельства' || arr_str[arr_str.length-1] === 'Иная' || arr_str[arr_str.length-1] === 'Невозможно прочесть адрес адресата') {
        if (arr_str[6] == '963416' && mode === 'fully') {
          // task
          fetch(pathGlo+'/v1/crm/set_task.php?deal='+arr_str[0]+'&mark=2&time=928800&comm=ОТПРАВЛЕНИЕ ВОЗВРАЩАЕТСЯ '+arr_str[arr_str.length-3].substr(0,10));
          // history
          fetch(pathGlo+'/v1/crm/set_deal_log.php?deal='+arr_str[0]+'&mark=2&comment=ОТПРАВЛЕНИЕ ВОЗВРАЩАЕТСЯ '+arr_str[arr_str.length-3].substr(0,10));
          // stage
          fetch(pathGlo+'/v1/crm/set_stage.php?deal='+arr_str[0]+'&stage=419655');
        }
        if (resdate > 59) {
          //emailing
          fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' невостребовано, со дня отправки прошло '+Math.round(resdate)+' дней. Последнее движение было '+arr_str[arr_str.length-3]+'.');
          // log
          toLogFile('Отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' невостребовано, со дня отправки прошло '+Math.round(resdate)+' дней. Последнее движение было '+arr_str[arr_str.length-3]+'.', 'WARNING');
        }
        console.log(arr_str[arr_str.length-1]);
      } else if (arr_str[arr_str.length-1] === 'Прибыло в сортировочный центр' || arr_str[arr_str.length-1] === 'Сортировка' || arr_str[arr_str.length-1] === 'Покинуло сортировочный центр' || arr_str[arr_str.length-1] === 'Покинуло место приёма' || arr_str[arr_str.length-1] === 'Покинуло место международного обмена' || arr_str[arr_str.length-1] ===  'Упрощенный предзаполненный') {
        // task
        /*if (resdate < 10) {
          var z = (10 - Math.round(resdate)) * 24 * 60 * 60+(64800);
        } else {
          z = 64800;
        }*/
        // task
        //fetch(pathGlo+'/v1/crm/set_task.php?deal='+arr_str[0]+'&time='+z+'&comm=Отправление находится в пути');
        if (resdate > 30) {
          //emailing
          //fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' в пути, но со дня отправки прошло '+Math.round(resdate)+' дней');
          // log
          toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' в пути, но со дня отправки прошло '+Math.round(resdate)+' дней', 'WARNING');
        } else if (resdate > 59) {
          //emailing
          fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' в пути, но со дня отправки прошло '+Math.round(resdate)+' дней. Последнее движение было '+arr_str[arr_str.length-3]+'.');
          // log
          toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' в пути, но со дня отправки прошло '+Math.round(resdate)+' дней. Последнее движение было '+arr_str[arr_str.length-3]+'.', 'WARNING');
        }
        console.log(arr_str[arr_str.length-1]);
      } else if (arr_str[arr_str.length-1] === 'Засылка') {
        toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+' ПЕРЕНАПРАВЛЕНО НА ВЕРНЫЙ АДРЕС. Со дня отправки прошло '+Math.round(resdate)+' дней', 'WARNING');
        // emailing
        fetch(pathGlo+'/mailsender.php?msg=Отправление по сделке № '+arr_str[1]+' с трек-номером '+arr_str[2]+'. ПЕРЕНАПРАВЛЕНО НА ВЕРНЫЙ АДРЕС. ВОЗМОЖНО ЭТО ВОЗВРАТ. Со дня отправки прошло '+Math.round(resdate)+' дней. ОТЛАДОЧНАЯ ИНФОРМАЦИЯ.&debug');
      } else {
        // emailing
        //fetch(pathGlo+'/mailsender.php?msg=отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+'. Ответ от почты: '+arr_str[arr_str.length-1]+', не определён в системе, возможно его нет в нашем списке');
        // log
        toLogFile('отправление по сделке № '+arr_str[0]+' с трек-номером '+arr_str[1]+'. Ответ от почты: '+arr_str[arr_str.length-1]+', не определён в системе, возможно его нет в нашем списке', 'ERROR');
        console.log(arr_str[arr_str.length-1]);
      }
    } else if (arr_str[0] === 'no any answer') {
      // date
        datetoday = new Date();
        if (arr_str[3]) {
          dateprepare = arr_str[3].split('.');
          dateorder = new Date(dateprepare[2],dateprepare[1]-1,dateprepare[0]);
          resdate = (datetoday - dateorder) / (1000 * 3600 * 24);
        } else {
          resdate = -1;
        }

      if (resdate > 3) {
        if (mode === 'fully') {
          // emailing
          fetch(pathGlo+'/mailsender.php?msg=Отправление по сделке № '+arr_str[1]+' с трек-номером '+arr_str[2]+'. С сервера почты не поступило никакого ответа ('+arr_str[0]+'). ОТПРАВЛЕНИЕ ОТПРАВЛЕНО БОЛЕЕ 3-х ДНЕЙ НАЗАД ВОЗМОЖНО ПРОБЛЕМА С ТРЕК НОМЕРОМ ИЛИ ТРЕК ОТСУТСТВУЕТ В БАЗЕ ПОЧТЫ РОССИИ, ТАК ЖЕ, ВОЗМОЖНО, ПОЧТОВАЯ СЛУЖБА НЕ ОПРЕДЕЛЕНА В НАШЕЙ СИСТЕМЕ.');
        }
        // log
        toLogFile('отправление по сделке № '+arr_str[1]+' с трек-номером '+arr_str[2]+'. Не поступило никакого ответа ('+arr_str[0]+'). ОТПРАВЛЕНИЕ ОТПРАВЛЕНО БОЛЕЕ 3-х ДНЕЙ НАЗАД. ВОЗМОЖНО ПРОБЛЕМА С ТРЕК НОМЕРОМ ИЛИ ТРЕК ОТСУТСТВУЕТ В БАЗЕ ПОЧТЫ РОССИИ, ТАК ЖЕ, ВОЗМОЖНО, ПОЧТОВАЯ СЛУЖБА НЕ ОПРЕДЕЛЕНА В НАШЕЙ СИСТЕМЕ.', 'ERROR');
      } else if (resdate === -1) {
        var dopstr = 'NONE';
        if (!Array.isArray(arr_str)) {
          dopstr = arr_str;
        }
        // log
        toLogFile('отправление по сделке № '+arr_str[1]+' с трек-номером '+arr_str[2]+'. Не поступило ответа ('+arr_str[0]+'). ДАТА ОТСУТСТВУЕТ. ВОЗМОЖНО ПРОБЛЕМА С ТРЕК НОМЕРОМ. ДОП. ДАННЫЕ '+dopstr, 'FATAL');
      } else {
        // log
        toLogFile('отправление по сделке № '+arr_str[1]+' с трек-номером '+arr_str[2]+'. Не поступило никакого ответа ('+arr_str[0]+'). ОТПРАВЛЕНИЕ ОТПРАВЛЕНО 3 или МЕНЕЕ 3-х ДНЕЙ НАЗАД. ВОЗМОЖНО ПРОБЛЕМА С ТРЕК НОМЕРОМ ИЛИ ТРЕК ОТСУТСТВУЕТ В БАЗЕ ПОЧТЫ РОССИИ, ТАК ЖЕ, ВОЗМОЖНО, ПОЧТОВАЯ СЛУЖБА НЕ ОПРЕДЕЛЕНА В НАШЕЙ СИСТЕМЕ.', 'WARNING');
      }
    } else {
      if (anssecond[string][1] === 'b') {
        // log
        toLogFile('СЕРВЕР ПОЧТЫ РОССИИ ВЕРНУЛ ОШИБКУ. ВОЗМОЖНО ПРОБЛЕМА С АВТОРИЗАЦИЕЙ ИЛИ ПРЕВЫШЕН ЛИМИТ. ТРЕБУЕТ УТОЧНЕНИЯ', 'FATAL');
      } else {
        // log
        toLogFile('СЕРВЕР ПОЧТЫ РОССИИ ВЕРНУЛ ОШИБКУ. ВОЗМОЖНО ОШИБКА СЕРВЕРА.', 'FATAL');
      }
    }
  }
  // emailing for manager (post)
  if (warningmessages2) {
  	fetch(pathGlo+'/mailsenderpost.php?msg&type=post', {
  		method: 'POST',
  		body: warningmessages2
  	});
  }
  console.log(anssecond);
  console.log(thirdvar);
}, secondtimewaitcrm);


setTimeout(function() {
	document.getElementById('spinner').src = 'api/download.png'
	document.getElementById('content').innerHTML = "СТАРТ<br>ИДЁТ ЗАПРОС И ОБРАБОТКА ОТВЕТА ИЗ СРМ<br>*****************<br>ПРОДОЛЖЕНИЕ<br>ИДЁТ ЗАПРОС И ОБРАБОТКА ОТВЕТА ИЗ ПОЧТЫ РОССИИ<br>*****************<br>ИДЁТ ЗАПРОС В СРМ<br>*****************<br>ДОПОЛНИТЕЛЬНЫЕ ПРОЦЕДУРЫ И ПРОВЕРКИ<br>*****************<br>ЗАВЕРШЕНО, МОЖНО ЗАКРЫТЬ ВКЛАДКУ<br>ВСЕГО ЗАПИСЕЙ ОБРАБОТАНО " +arr.length;
}, timewaitfinal);

</script>
</body>
</html>
<?php
# BEGIN
# END
?>
