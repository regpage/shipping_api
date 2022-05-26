<script type="text/javascript">
	let msg = "Тестовое отправление";
		fetch("api/mailsender.php?msg=" + msg);
		fetch('api/mailsenderpost.php?msg&type=post', {
			method: 'POST',
			body: msg
		});
</script>
