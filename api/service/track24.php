<?php
//https://reg.new-constellation.ru/api/service/track24.php?tn=42605660103274

$tn = $_GET['tn'];
$id = $_GET['id'];
?>

<script>
var tnumber = '<?php echo $tn;?>';
var ansby=[];
//var pathGlo = 'api';
// TRACK24
// BELARUS POST

//setTimeout(function () {
  for (var jj = 0; jj < 1;  jj++) {//arrby.length
    //pathby = pathGlo+'/v1/track24/client_tr24.php?tn='+arrby[jj][1]+'&id='+arrby[jj][0]+'&date='+arrby[jj][2]+'&empl='+arrby[jj][3]+'&user='+arrby[jj][4]+'&client='+arrby[jj][5]+'&stage='+arrby[jj][6];
    ansby.push([tnumber,1,2,3,4,5]);
    pathby = '../v1/track24/client_tr24.php?tn='+tnumber;
    fetch(pathby)
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      ansby.push(data);
    });
  }
//}, 9000);
console.log(ansby);

setTimeout(function () {
  console.log(ansby[1]['data']['lastPoint']['operation']);

  for (var jjby = 0; jjby < 1;  jjby=jjby+2) {//ansby.length
    if (ansby[jjby+1]['data']) {
      if (ansby[jjby+1]['data']['lastPoint']['operation'] === 'сортировка') {

      } else if (ansby[jjby+1]['data']['lastPoint']['operation'] === 'Срок хранения истек. Выслано обратно отправителю') {

      } else if (ansby[jjby+1]['data']['lastPoint']['operation'] === 'Неудачная попытка вручения' || ansby[jjby+1]['data']['lastPoint']['operation'] === 'Ожидает адресата в месте вручения') {
        console.log(ansby[1]['data']['lastPoint']['operation']);
      }
    }

  }
}, 3000);

</script>

<?php
?>
