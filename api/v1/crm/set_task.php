<?php

if (!isset($_GET['deal'])) {
	exit;
}

//https://reg.new-constellation.ru/api/v1/crm/set_task.php?deal=39738059&time=64800&mark=2&comm=ВОЗВРАТ_ОЖИДАЕТ_С_21-07-2021:_МОСКВА_6

$deal = $_GET['deal']*1;
$pretime = $_GET['time'];
$time = strtotime(date("Y-m-d")) + $pretime;
$comment = $_GET['comm'];
if ($_GET['mark'] == 1 && isset($_GET['mark'])) {
	$comment = '✅ '. $comment;
} elseif ($_GET['mark'] == 2 && isset($_GET['mark'])) {
	$comment = '⬛ '. $comment;
}

## BEGIN

$link = 'https://bibleforall.envycrm.com/crm/api/v1/deal/updatetask/?api_key=';
$curl = curl_init();

$data = [
  "deal_id" => $deal,
  "time" => $time,
  "comment" => $comment
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
//echo $answer['message']. ' & '.$answer['result'][0]['values']['custom']['crm_132909']['value'];

## END

?>
