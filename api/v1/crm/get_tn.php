<?php

$offset = $_GET['offset'];
$stage = $_GET['stage'];

  ## BEGIN data POST sends directly VERSION 2



    $link = 'https://bibleforall.envycrm.com/crm/api/v1/deal/list/?api_key=ecdfd3e079da4ab92942a50d8dd67991b5878f21';
    $curl = curl_init();

    $data = [
      'stage_id' => $stage,
      'limit' => 50,
      'offset' => $offset //ПОМЕСТИТЬ В ЦИКЛ И ПОЛУЧАТЬ ПО 50 ЗАПИСЕЙ ПОКА НЕ ВЕРНЁТСЯ ПУСТОЙ ОТВЕТ И ТОГДА ЗАВЕРШИТЬ НАПОЛНЕНИЕ МАССИВА ИЛИ ЗАПРОСЫ К ПОЧТОВИКАМ, КАК ПОЛУЧИТСЯ
    ];

    //curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_URL, $link);
    curl_setopt($curl,CURLOPT_POST,true);
    curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode(['request' => $data]));
    curl_setopt($curl,CURLOPT_HEADER,false);

    $out=curl_exec($curl);
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    curl_close($curl);
    $answer = json_decode($out, true);
    //return $answer;
    //echo $answers['result'];

  # END
?>
