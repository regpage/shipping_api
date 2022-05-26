<?php

# Get data from CRM

/**
 * Примерный вариант, возможно потребуется один клас для всех GET и POST запросов
 * Либо он возвращает данные, а ошибки хранит в статической переменной, либо всё хранит в переменных например для логов
 */

class GetRemoteData {

  # variables
  private $error_answer;
  static $answer;
  private $errors;

  # Get data
  static function getdata() {
    return self::$answer;
  }

  # Get error data
  function getdataerror() {
    return $error_answer;
  }

  # Get errors
  function geterrors() {
    return $errors;
  }
}

/**
 * Кого на каком родителе делать?
 */
class GetTN extends GetRemoteData {
  # Constructor
  function __construct() {
    # BEGIN
    $stage = 419655;
    $offset = 0;
    // получаем данные из СРМ по 50 за цикл и складываем в переменную.
    for ($i=0; $i < 10; $i++) {
        $link = 'https://bibleforall.envycrm.com/crm/api/v1/deal/list/?api_key=ecdfd3e079da4ab92942a50d8dd67991b5878f21';
        $curl = curl_init();
        $data = [
          'stage_id' => $stage, // Этап сделки, это условие отбора
          'limit' => 50, // 50 это максимум, сколько можно получить записей из срм за один запрос
          'offset' => $offset // Позиция с которой начинается отбор в СРМ
        ];

        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_URL, $link);
        curl_setopt($curl,CURLOPT_POST,true);
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode(['request' => $data]));
        curl_setopt($curl,CURLOPT_HEADER,false);

        $out=curl_exec($curl);
        $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
        curl_close($curl);
        $result = json_decode($out, true);
        // Игнорировать пустые массивы (в  дальнейшем автоматически рассчитывать число итераций)
        // добавить исключение для ошибки сервера, что бы вывести предупреждение
        // если отобрано  0 записей сигналить в ответе. Ответ отправлять на почту после каждого запроса
        if ($i === 0 && $result['result'][0]['id']) {
          self::$answer = $result['result'];
        } else if ($i !== 0 && $result['result'][0]['id']){
          self::$answer = array_merge(self::$answer, $result['result']);
        }

        // Настраиваем данные для следующего запроса.
        $offset = $offset + 50;
        // 5 стадий
        if ($stage === 419655 && $i === 5) {
          $stage = 963416;
          $offset = 0;
        }
    }
    # END
  }
}

?>
