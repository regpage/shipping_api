<?php

# Filter data CRM

class Filterdatacrm {
  private $filter;
  # Constructor
  function __construct() {
    # BEGIN
    // Получаем данные ответа от СРМ в виде массива.
    $filter = GetTN::getdata();
    if (count($filter) > 0) {
      $msg_error_tracknumber = '';
      // Подсчитываем количество строк
      // ПЕРЕМЕННАЯ ВЫШЕ НЕ ОПРЕДЕЛЕНА!
      //stringcount = stringcount + data['result'].length;
      // Перебираем и фильтруем массив с данными из СРМ
      foreach ($filter as $key=>$value) {
        if ($value['values']['custom']['crm_131685']['value'] === 'Почта' && $value['values']['custom']['crm_132909']['value'][0] !== '-' && $value['values']['custom']['crm_132909']['value'][1] !== '-') {
          if ($value['values']['custom']['crm_132909']['value']) {
            $x = $value['values']['custom']['crm_132909']['value'];
            $y = explode(" ", $x); // проверяем на пробелы
            $z = explode(",", $x); // два трек-номера в одном отправлении
            if ($z[1]) {
              /*
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
              */
            }
          } else {
            $msg_error_tracknumber = 'Отправление id '. $value['id'] .', от '. $value['values']['custom']['crm_131481']['value'] .', на имя ' + $value['client_name'] . ', НЕ УКАЗАН ТРЕК-НОМЕР, Трек-номер: '. $value['values']['custom']['crm_132909']['value'];
          }
        } else {
  					$msgdtl1 = 'NONE';
  					$msgdtl2 = 'NONE';
  					$ignor++;
  					if ($value['values']['custom']['crm_131481']) {
  						$msgdtl1 = $value['values']['custom']['crm_131481']['value'];
  					}
  					if ($value['values']['custom']['crm_132909']) {
  						$msgdtl2 = $value['values']['custom']['crm_132909']['value'];
  					}
            $msg_info_tracknumber = 'Отправление id '. $value['id'] .', от '. $msgdtl1 .', на имя ' . $value['client_name'] . ', ИСКЛЮЧЕНО из запроса (отфильтровано), Трек-номер: '. $msgdtl2;
  					if ($value['values']['custom']['crm_132909']) {
  						if ($value['values']['custom']['crm_132909']['value'][0] !== '-' && $value['values']['custom']['crm_132909']['value'][1] !== '-') {
                $msg = 'Отправление id '. $value['id'] .', трек-номер: '. $msgdtl2. ' от '. $msgdtl1 .', на имя ' . $value['client_name'] . ' ИСКЛЮЧЕНО из запроса НЕ ПО ПРИЗНАКУ "--". Требует выяснения.';
                Email_sender::send_email($msg, 'debug');
  						}
  					} else {
                $msg = 'Отправление id '. $value['id'] .', трек-номер: '. $msgdtl2.' от '. $msgdtl1 .', на имя ' . $value['client_name'] . ' ИСКЛЮЧЕНО из запроса, отсутствует трек-номер. Возможно вручается лично или требует выяснения.';
                Email_sender::send_email($msg, 'debug');
  					}
        }
        /*
              if (z[1]) {
                ztrack1 = z[0];
                ztrack2 = z[1];
                ztrack1 = ztrack1.trim();
                ztrack2 = ztrack2.trim();
                if ((ztrack1.length === 14 && ztrack2.length === 14) || (ztrack1[11] == 'R' && ztrack1[12] == 'U'  && ztrack1.length === 13 && ztrack2[11] == 'R' && ztrack2[12] == 'U' && ztrack2.length === 13)) {
                  arr.push ([$value['id'], ztrack1, $value['values']['custom']['crm_131481']['value'], $value['employee_id'], $value['user_id'], $value['client_id'], $value['stage_id'], $value['client_name']]);
                  arr.push ([$value['id'], ztrack2, $value['values']['custom']['crm_131481']['value'], $value['employee_id'], $value['user_id'], $value['client_id'], $value['stage_id'], $value['client_name']]);
                } else if (ztrack1.length === 13 && ztrack1[11] === 'B' && ztrack1[12] === 'Y' && ztrack2.length === 13 && ztrack2[11] === 'B' && ztrack2[12] === 'Y') {
                  arrby.push ([$value['id'], ztrack1, $value['values']['custom']['crm_131481']['value'], $value['employee_id'], $value['user_id'], $value['client_id'], $value['stage_id']]);
                  arrby.push ([$value['id'], ztrack2, $value['values']['custom']['crm_131481']['value'], $value['employee_id'], $value['user_id'], $value['client_id'], $value['stage_id']]);
                } else if (ztrack1.length === 13 && ztrack2.length === 13) {
                  arrint.push ([$value['id'], ztrack1, $value['values']['custom']['crm_131481']['value'], $value['employee_id'], $value['user_id'], $value['client_id'], $value['stage_id']]);
                  arrint.push ([$value['id'], ztrack2, $value['values']['custom']['crm_131481']['value'], $value['employee_id'], $value['user_id'], $value['client_id'], $value['stage_id']]);
                  msg_error_tracknumber = 'Отправление id '+ $value['id'] +', от '+ $value['values']['custom']['crm_131481']['value'] +', на имя ' + $value['client_name'] + ', ТРЕК-НОМЕР НЕ ОПОЗНАН, Трек-номера: '+ $value['values']['custom']['crm_132909']['value'];
                } else {
                  arrerr.push ([$value['id'], ztrack1, $value['values']['custom']['crm_131481']['value'], $value['employee_id'], $value['user_id'], $value['client_id'], $value['stage_id']]);
                  arrerr.push ([$value['id'], ztrack2, $value['values']['custom']['crm_131481']['value'], $value['employee_id'], $value['user_id'], $value['client_id'], $value['stage_id']]);
                  msg_error_tracknumber = 'Отправление id '+ $value['id'] +', от '+ $value['values']['custom']['crm_131481']['value'] +', на имя ' + $value['client_name'] + ', ТРЕК-НОМЕР НЕ ПРОШЁЛ ПРОВЕРКУ, Трек-номер: '+ $value['values']['custom']['crm_132909']['value'];
                }
              } else if (!y[1]) {
                if (x.length === 14 || (x.length === 13 && x[11] == 'R' && x[12] == 'U')) {
                  arr.push ([$value['id'], $value['values']['custom']['crm_132909']['value'], $value['values']['custom']['crm_131481']['value'], $value['employee_id'], $value['user_id'], $value['client_id'], $value['stage_id'], $value['client_name']]);
                } else if (x[12] == 'Y' && x[11] == 'B' && x.length === 13) {
                   arrby.push ([$value['id'], $value['values']['custom']['crm_132909']['value'], $value['values']['custom']['crm_131481']['value'], $value['employee_id'], $value['user_id'], $value['client_id'], $value['stage_id']]);
                 } else if (x.length === 13) {
                   arrint.push ([$value['id'], $value['values']['custom']['crm_132909']['value'], $value['values']['custom']['crm_131481']['value'], $value['employee_id'], $value['user_id'], $value['client_id'], $value['stage_id']]);
                   msg_error_tracknumber = 'Отправление id '+ $value['id'] +', от '+ $value['values']['custom']['crm_131481']['value'] +', на имя ' + $value['client_name'] + ', ТРЕК-НОМЕР НЕ ОПОЗНАН, Трек-номер: '+ $value['values']['custom']['crm_132909']['value'];
                 } else {
                   arrerr.push ([$value['id'], $value['values']['custom']['crm_132909']['value'], $value['values']['custom']['crm_131481']['value'], $value['employee_id'], $value['user_id'], $value['client_id'], $value['stage_id']]);
                   msg_error_tracknumber = 'Отправление id '+ $value['id'] +', от '+ $value['values']['custom']['crm_131481']['value'] +', на имя ' + $value['client_name'] + ', ТРЕК-НОМЕР НЕ ПРОШЁЛ ПРОВЕРКУ, Трек-номер: '+ $value['values']['custom']['crm_132909']['value'];
                 }
             } else {
                 msg_error_tracknumber = 'Отправление id '+ $value['id'] +', от '+ $value['values']['custom']['crm_131481']['value'] +', на имя ' + $value['client_name'] + ', ТРЕК-НОМЕР ОТСУТСТВУЕТ или С ПРОБЕЛАМИ, Трек-номер: '+ $value['values']['custom']['crm_132909']['value'];
             }
           }
         }
        }
        if ($msg_error_tracknumber) {
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
        // Возможно лучше использовать отдельный класс и место для Логов
        // Лог ниже должен быть результирующий и отправлющийся в браузер илои в ответ в крон
        // toLogFile('Предоставлено пустых элементов массива из CRM '+emptystring, 'INFO');
        */
      }
    }
    # END
  }
}

?>
