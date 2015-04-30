<?php
/*header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");*/

$data = json_decode(file_get_contents("php://input"));
/*$usrname = mysql_real_escape_string($data->exhibit_Id);
$upswd = mysql_real_escape_string($data->user_Id);*/
$rating = mysql_real_escape_string($data->rating);
 echo $rating;
?>