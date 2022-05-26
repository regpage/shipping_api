<?php

# Рассыльщик емайл

/**
 *
 *
 */

class Email_sender {

  # Send email
  function send_email ($message, $debug = false) {

    # to
    if ($debug) {
    	$to = 'zhichkinroman@gmail.com';
    } else {
    	$to = 'zhichkinroman@gmail.com, a.rudanok@gmail.com, and1ievsky@gmail.com';
    }

    # topic
    $subject = '=?utf-8?B?'.base64_encode('Проверка почтовых отправлений').'?=';

    # headers
    #bibleforall
    $headers = 'From: noreply@reg.new-constellation.ru.ru' . "\r\n" .
    'Content-Type: text/html; charset=utf-8' . "\r\n" .
    'Reply-To: zhichkinroman@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

    # message
    $message = wordwrap($message, 70, "\r\n");

    # send
    mail($to, $subject, $message, $headers);
  }
}

?>
