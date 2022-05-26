<?php

if (!isset($_GET['key'])) {
	exit;
}

//https://reg.new-constellation.ru/api/pr/crminfo.php?key
## BEGIN

$link = 'https://bibleforall.envycrm.com/crm/api/v1/main/data/?api_key=ecdfd3e079da4ab92942a50d8dd67991b5878f21';
$curl = curl_init();

//$data = ["" => $];

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
