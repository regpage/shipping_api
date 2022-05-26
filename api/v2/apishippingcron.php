<?php

# Сделать лог. Старый лог можно оставить функцией.
# Текст статусов исполнения ключевых запросов и вызовов записывать в статическую переменнную
# специально созданного класа и в результате выводить echo в при завершении программы.

# Подключаем классы
include('classes/getdatafromcrm.php');
include('classes/filterdatacrm.php');
include('classes/emailsender.php');

# Получаем данные из СРМ
$crm_data = new GetTN;
echo "<h1>Выполнение запроса для BFA</h1>";
echo "<h2>СТАРТ</h2>";
echo "<p>Получаем данные из CRM...</p>";
$counter = count($crm_data->getdata());
echo "<p>Получено $counter записей из CRM.</p>";
echo "<hr>";
echo "<h2>Подготавливаем данные для запроса</h2>";
echo "<p>Формируем массив для запроса.</p>";
$prepare_data = new Filterdatacrm;

?>
