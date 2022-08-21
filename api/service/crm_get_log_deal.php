<?php

if (!isset($_GET['deal'])) {
	exit;
}

//https://reg.new-constellation.ru/api/service/crm_get_log_deal.php?deal
$deal = $_GET['deal'];
$limit = '0';
$offset = '0';

## BEGIN

$link = 'https://bibleforall.envycrm.com/crm/api/v1/deal/log/list/?api_key=&deal_id='.$deal.'&limit='.$limit.'&offset='.$offset;
$curl = curl_init();

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
