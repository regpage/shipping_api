<?php

if (!isset($_GET['deal']) || empty($_GET['deal'])) {
	echo "ВВЕДИТЕ ID СДЕЛКИ ПОСЛЕ ЗНАКА РАВНО В ЗАПРОСЕ";
	exit;
} elseif (!is_numeric($_GET['deal'])) {
	echo "ID СДЕЛКИ МОЖЕТ СОДЕРЖАТЬ ТОЛЬКО ЦИФРЫ";
	exit;
}

//https://reg.new-constellation.ru/api/v1/crm/get_deal.php?deal=

$deal = $_GET['deal']*1;

## BEGIN

$link = 'https://bibleforall.envycrm.com/crm/api/v1/deal/get/?api_key=ecdfd3e079da4ab92942a50d8dd67991b5878f21';
$curl = curl_init();

$data = [
  "deal_id" => $deal
];

//curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_URL, $link);
curl_setopt($curl,CURLOPT_POST,true);
curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode(['request' => $data]));
curl_setopt($curl,CURLOPT_HEADER,false);

$out=curl_exec($curl);
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
curl_close($curl);

//$answer = json_decode($out, true);
//echo $answer['result'];

## END

?>