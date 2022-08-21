<?php

if (!isset($_GET['tn'])) {
	exit;
}
$nf = $_GET['nf'];
$tn = $_GET['tn'];
$id = $_GET['id'];
$date = $_GET['date'];
$empl = $_GET['empl'];
$user = $_GET['user'];
$client = $_GET['client'];
$stage = $_GET['stage'];
$fio = $_GET['fio'];


if ($nf === '1') {
 $loglog = '';
 $paspas = '';
} else if ($nf === '2') {
	$loglog = '';
  $paspas = '';
} else if ($nf === '3') {
	$loglog = '';
  $paspas = '';
} else if ($nf === '4') {
	$loglog = '';
  $paspas = '';
} else if ($nf === '5') {
	$loglog = '';
  	$paspas = '';
}

$wsdlurl = 'https://tracking.russianpost.ru/rtm34?wsdl';
$client2 = '';

$client2 = new SoapClient($wsdlurl, array('trace' => 1, 'soap_version' => SOAP_1_2));

$params3 = array ('OperationHistoryRequest' => array ('Barcode' => $tn, 'MessageType' => '0','Language' => 'RUS'), 'AuthorizationHeader' => array ('login'=>$loglog,'password'=>$paspas));


$result = $client2->getOperationHistory(new SoapParam($params3,'OperationHistoryRequest'));
if ($result->OperationHistoryData->historyRecord) {
	$count = 0;
	foreach ($result->OperationHistoryData->historyRecord as $record) {
		if ($count === 0) {
			echo $id.'-o-o->'.$tn.'-o-o->'.$date.'-o-o->'.$empl.'-o-o->'.$user.'-o-o->'.$client.'-o-o->'.$stage.'-o-o->'.$fio.'-o-o->'.$record->OperationParameters->OperDate.'-o-o->'.$record->AddressParameters->OperationAddress->Description. '-o-o->'.$record->OperationParameters->OperAttr->Name;
			$count++;
		} else {
			echo '-o-o->'.$record->OperationParameters->OperDate.'-o-o->'.$record->AddressParameters->OperationAddress->Description. '-o-o->'.$record->OperationParameters->OperAttr->Name;
		}
	};
} else {
	echo 'no any answer-o-o->'.$id.'-o-o->'.$tn.'-o-o->'.$date.'-o-o->'.$empl.'-o-o->'.$user.'-o-o->'.$client.'-o-o->'.$stage;
};

?>
