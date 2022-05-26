<?php
if (!isset($_GET['msg'])) {
	exit;
}
if (isset($_GET['debug'])) {
	$men = 'zhichkinroman@gmail.com, info@new-constellation.ru';
} else {
	$men = 'zhichkinroman@gmail.com, a.rudanok@gmail.com, and1ievsky@gmail.com, info@new-constellation.ru';
}
//$subject = 'Проверка почтовых отправлений';
//bibleforall.ru
$subject = '=?utf-8?B?'.base64_encode('Проверка почтовых отправлений').'?=';
$headers = 'From: noreply@reg.new-constellation.ru' . "\r\n" .
'Content-Type: text/html; charset=utf-8' . "\r\n" .
'Reply-To: zhichkinroman@gmail.com' . "\r\n" .
'X-Mailer: PHP/' . phpversion();
$to = $men;
$message = $_GET['msg']; // for Windows $text = str_replace("\n.", "\n..", $text);
mail($to, $subject, $message, $headers);
