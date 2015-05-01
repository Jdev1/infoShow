<?php
/*header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");*/

$data = json_decode(file_get_contents("php://input"));

$rating = mysql_real_escape_string($data->rating);
$id = mysql_real_escape_string($data->tag);
 echo $rating;
?>