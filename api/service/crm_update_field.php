<?php

if (!isset($_GET['deal']) && empty($_GET['deal']) && !isset($_GET['field']) && empty($_GET['field']) && !isset($_GET['newvalue']) && empty($_GET['newvalue']) && !isset($_GET['type']) && empty($_GET['type'])) {
	exit;
}

//https://reg.new-constellation.ru/api/service/crm_update_field.php?deal&field&newvalue&type
$deal = $_GET['deal'];
$empl = '0';//'502289'
$user = '229971';
$client = '45907622';
$newvalue = $_GET['newvalue'];
$field = $_GET['field'];
$type = $_GET['type'];//'3'

## BEGIN

$link = 'https://bibleforall.envycrm.com/crm/api/v1/deal/updateDealValue/?api_key=';
$curl = curl_init();

$data = [
  "deal_id" => $deal,
  "client_id" => $client,
  "employee_id" => $empl,
  "user_id" => $user,
  "fields" => [
    "custom" => [
      "input_id" => $field,
      "value" => $newvalue,
      "value_type_id" => $type
    ]
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
//echo $answer['message']. ' & '.$answer['result'][0]['values']['custom']['crm_132909']['value'];

## END

?>
