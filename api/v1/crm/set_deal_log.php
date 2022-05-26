<?php

if (!isset($_GET['deal'])) {
	exit;
}

//https://reg.new-constellation.ru/api/v1/crm/set_deal_log.php
$deal = $_GET['deal']*1;
$empl = 0;
$comment = $_GET['comment'];
$type = 2010;

if ($_GET['mark'] == 1 && isset($_GET['mark'])) {
	$comment = '✅ '. $comment;
} elseif ($_GET['mark'] == 2 && isset($_GET['mark'])) {
	$comment = '⬛ '. $comment;
}

## BEGIN

$link = 'https://bibleforall.envycrm.com/crm/api/v1/deal/log/create/?api_key=ecdfd3e079da4ab92942a50d8dd67991b5878f21';
$curl = curl_init();

$data = [
  "deal_id" => $deal,
  "employee_id" => $empl,
  "type_id" => $type,
  "data" => [
    "comment" => $comment
  ]
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
echo $answer['result'];

## END

?>
