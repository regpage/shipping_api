<?php

//https://reg.new-constellation.ru/api/v1/track24/client_tr24.php?tn=

if (!isset($_GET['tn']) && empty($_GET['tn'])) {
	exit;
}

$key = '79455b6bffc1c4f998a88d25c5a8d6b7';
$domen = 'bibleforall.ru';
$pretty='true';
$tn = $_GET['tn'];

$link = 'https://api.track24.ru/tracking.json.php?apiKey='.$key.'&domain='.$domen.'&pretty='.$pretty.'&code='.$tn;

$curl = curl_init();
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
?>
