<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
	</head>	
	<body>
		<img id="spinner" src="api/download.gif">
		<div id="content"></div>
<script>
var pathGlo = 'api';
var stages = 419655;
var offset = 0;
var i = 0;
while (i < 10) {	
  fetch(pathGlo+'/v1/crm/get_tn.php?stage='+stages+'&offset='+offset)
  .then((response) => {
    return response.json();
  })
  .then((data) => {
   	if (data['result'].length !== 0) {
     	console.log(data['result']);			
 		}
	})	
	i++;
  offset = offset + 50;  
  if (stages === 419655 && i === 5) {
  	stages = 963416;
    offset = 0;
  }
  
} 
</script>
</body>
</html>