<?php

//https://reg.new-constellation.ru/apitest/service/one_pr_client.php?nf=4&tn=

if (!isset($_GET['tn']) && empty($_GET['tn'])) {
	exit;
}
$nf = $_GET['nf'];
$tn = $_GET['tn'];
/*$id = $_GET['id'];
$date = $_GET['date'];
$empl = $_GET['empl'];
$user = $_GET['user'];
$client = $_GET['client'];
$stage = $_GET['stage'];*/


if ($nf === '1') {
 $loglog = 'PhUOdFAsZwiQeO';
 $paspas = '5GRHRg4YW1e5';
} else if ($nf === '2') {
	$loglog = 'mbRfEbqLavdAHl';
  $paspas = '0xO6oi0CbrVB';
} else if ($nf === '3') {
	$loglog = 'NFkDhMTdIRKEXl';
  $paspas = 'nzjcSb6DLXX6';
} else if ($nf === '4') {
	$loglog = 'gStclSWXPAayaf';
  $paspas = 'fBvDOdukiGQA';
} else if ($nf === '5') {
	$loglog = 'EaSMKNvZcUlzhD';
  	$paspas = 'AX68HXvQSPEs';
}


$wsdlurl = 'https://tracking.russianpost.ru/rtm34?wsdl';
$client2 = '';

$client2 = new SoapClient($wsdlurl, array('trace' => 1, 'soap_version' => SOAP_1_2));

$params3 = array ('OperationHistoryRequest' => array ('Barcode' => $tn, 'MessageType' => '0','Language' => 'RUS'), 'AuthorizationHeader' => array ('login'=>$loglog,'password'=>$paspas));

$result = $client2->getOperationHistory(new SoapParam($params3,'OperationHistoryRequest'));
if ($result->OperationHistoryData->historyRecord) {
	foreach ($result->OperationHistoryData->historyRecord as $record) {
			echo '-o-o->'.$record->OperationParameters->OperDate.'-o-o->'.$record->AddressParameters->OperationAddress->Description. '-o-o->'.$record->OperationParameters->OperAttr->Name;
	};
} else {
	echo 'no any answer';
};

?>
