<?php

if (!isset($_GET['client']) || empty($_GET['client'])) {
	echo "ВВЕДИТЕ ID СДЕЛКИ ПОСЛЕ ЗНАКА РАВНО В ЗАПРОСЕ";
	exit;
} elseif (!is_numeric($_GET['client'])) {
	echo "ID СДЕЛКИ МОЖЕТ СОДЕРЖАТЬ ТОЛЬКО ЦИФРЫ";
	exit;
}

//https://reg.new-constellation.ru/api/service/crmgetclient.php?client=

$client = $_GET['client']*1;

## BEGIN

$link = 'https://bibleforall.envycrm.com/crm/api/v1/client/get/?api_key=';
$curl = curl_init();

$data = [
  "client_id" => $client
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
