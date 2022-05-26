<?php
	# html data-type is cleaner_btn,
?>
<!DOCTYPE html>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="api/apipanel.js"></script>
		<link rel="stylesheet" type="text/css" href="api/apipanel.css">
		<title>API PANEL</title>
	</head>
	<body>
		<h1>Управление API для BFA</h1>
		<div class="regular-div">
			<p class="bold-text">КОМПЛЕКСНЫЕ ПАКЕТНЫЕ ЗАПРОСЫ</p>
			<p>ТЕСТОВЫЙ ЗАПРОС на три записи без уведомлений.</p>
			<a id="testing-link" href="/testapishipping.php?mode" title="ТЕСТОВЫЙ ПРЕД-ЗАПРОС С РАБОЧИМИ ФАЙЛАМИ (запускается из тестового файла), на 3 записи, задания не будут созданы.">Перейдите по ссылке что бы выполнить тестовый запрос</a>
		</div>
		<hr>
		<div class="regular-div mark-div">
			<p>РАБОЧИЙ ЗАПРОС</p>
			<a  id="operating-link" href="/apishipping.php?mode=fully" title="РАБОЧИЙ ЗАПРОС ДЛЯ СОЗДАНИЯ ЗАДАНИЙ В CRM">Перейдите по ссылке что бы выполнить ПАКЕТНЫЙ рабочий запрос</a>
		</div>
		<hr>
		<div>
			<div class="regular-div">
				<p class="bold-text">ЕДИНИЧНЫЕ СИСТЕМНЫЕ ЗАПРОСЫ</p>
			</div>
			<div class="regular-div mark-div">
				<p>ЕДИНИЧНЫЙ РАБОЧИЙ ЗАПРОС. По номеру сделки будет получен трэк-номер и созданы задания в CRM</p>
				<a  id="operating-link" href="/apishipping.php?mode=fully&deal=" title="РАБОЧИЙ ЗАПРОС ДЛЯ СОЗДАНИЯ ЗАДАНИЙ В CRM">Перейдите по ссылке что бы выполнить ЕДИНИЧНЫЙ рабочий запрос</a>
				<!--<button id="complex-query-deal-id" data-link="/apishipping.php?mode=fully&deal=" title="Заполните поле справа и кликните что бы выполнить запрос для сделки.">Единичный рабочий запрос по номеру сделки</button>
				<span> Введите номер сделки здесь -> </span>
				<input type="number" name="" title="Введите номер сделки" data-type="field_for_input">
				<button data-type="cleaner_btn">Очистить</button>
				<div class="div-med regular-div" data-type="show_result"> --- </div>-->
			</div>
			<hr>
			<div class="regular-div">
				<p>CRM</p>
				<button id="crm-deal-by-id" data-link="/api/service/crmgetdeal.php?deal=">Сделка из CRM по номеру сделки</button>
				<span> Введите номер сделки здесь -> </span>
				<input type="number" name="" title="Введите номер сделки" data-type="field_for_input">
				<button data-type="cleaner_btn">Очистить</button>
				<div class="div-med regular-div" data-type="show_result"> --- </div>
			</div>
			<hr>
			<div class="regular-div">
				<p>ПОЧТА РОССИИ</p>
				<button id="mail-info-by-tn" data-link="/api/service/one_pr_client.php?nf=5&tn=">Данные из Почты России по трэк-номеру</button>
				<span> Введите трэк-номер Почты России здесь -> </span>
				<input type="number" name="" title="Введите трэк-номер" data-type="field_for_input">
				<button data-type="cleaner_btn">Очистить</button>
				<div class="div-med regular-div" data-type="show_result"> --- </div>
			</div>
			<hr>
			<div class="regular-div">
				<p>CRM</p>
				<button id="crm-active-task-by-id" data-link="/api/service/crmgettask.php?deal=">Активная задача из CRM по номеру сделки</button>
				<span> Введите номер сделки здесь -> </span>
				<input type="number" name="" title="Введите номер сделки" data-type="field_for_input">
				<button data-type="cleaner_btn">Очистить</button>
				<div class="div-med regular-div" data-type="show_result"> --- </div>
			</div>
			<hr>
			<div class="regular-div">
				<p>CRM</p>
				<button id="crm-log-deal-by-id" data-link="/api/service/crm_get_log_deal.php?deal=">Лог сделки из CRM по номеру сделки</button>
				<span> Введите номер сделки здесь -> </span>
				<input type="number" name="" title="Введите номер сделки" data-type="field_for_input">
				<button data-type="cleaner_btn">Очистить</button>
				<div class="div-med regular-div" data-type="show_result"> --- </div>
			</div>
			<div class="regular-div">
				<p>CRM</p>
				<button id="crm-client-by-id" data-link="/api/service/crmgetclient.php?client=">Клиент из CRM по ID клиента</button>
				<span> Введите ID клиента здесь -> </span>
				<input type="number" name="" title="Введите ID клиента здесь" data-type="field_for_input">
				<button data-type="cleaner_btn">Очистить</button>
				<div class="div-med regular-div" data-type="show_result"> --- </div>
			</div>
			<hr>
		</div>
	</body>
</html>
