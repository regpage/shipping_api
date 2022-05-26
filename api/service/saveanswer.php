<?php
if (!isset($_GET['key'])) {
	exit;
}

date_default_timezone_set ("Europe/Moscow");

$db = new mysqli('localhost', 'u0654_testadmin', 'Reg-Page_2020', 'u0654376_testRegPage');

$db->set_charset('utf8');
if ($db->connect_errno) die('Could not connect: '.$mysqli->connect_error);

function db_query ($query){
  global $db;
  $res=$db->query ($query);
  if (!$res) throw new Exception ($db->error);
  return $res;
}

//$resres = json_decode($_POST, true);
//$resres = json_decode(file_get_contents("php://input"), true);
$resres = file_get_contents("php://input");
$query = $_GET['key'];
//$answer = $resres['answer'];
//$other = $resres['other'];
db_query ("INSERT INTO apibfa (`other`,`query`) VALUES ('$resres', '$query')");

?>
