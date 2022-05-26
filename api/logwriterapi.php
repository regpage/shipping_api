<?php

if (!isset($_GET['message'])) {
	exit;
}

// LOG FILE

function logFileWriter($message, $type='WARNING')
{
  $logAdminName = 'SERVER API ';

  $file = 'logs/logFile_API_'.date("d-m-Y").'.log';
  //Добавим разделитель, чтобы мы смогли отличить каждую запись
  $text = $type.' ==================================================='.PHP_EOL;
  $text .=  date('d-m-Y H:i:s') .PHP_EOL; //Добавим актуальную дату после текста или дампа массива
  $text .= $logAdminName.$message.PHP_EOL.PHP_EOL;

  $fOpen = fopen($file,'a'); //Открываем файл или создаём если его нет
  fwrite($fOpen, $text); //Записываем
  fclose($fOpen); //Закрываем файл
}

logFileWriter($_GET['message'], $_GET['type']);

?>
