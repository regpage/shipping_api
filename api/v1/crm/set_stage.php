<?php
//include_once 'db.php';
//include_once 'logWriter.php';
/**/
if (!isset($_GET['deal'])) {
	exit;
}

//https://reg.new-constellation.ru/api/pr/crmstage.php
$deal = $_GET['deal'];
$stage = $_GET['stage'];
$employee = 0;

## BEGIN

$link = 'https://bibleforall.envycrm.com/crm/api/v1/deal/updateDealStage/?api_key=';
$curl = curl_init();

$data = [
  "deal_id" => $deal,
  "stage_id" => $stage,
  "employee_id" => $employee
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
