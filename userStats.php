<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'database.php';
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "infoshow");

$database = new Database();

//Get Number of Exhibits
/*$database->query('SELECT * from exhibit');
$rows = $database->resultSet();
$exhibitCount = $database->rowCount();


$database->query("SELECT Exhibit_Title, Exhibit_ID FROM exhibit");
$exhibits = $database->resultSet();*/

$database->query("SELECT User_ID , COUNT(  `User_ID` ) c FROM interaction GROUP BY  `User_ID` ORDER BY c DESC LIMIT 1 ");
$mostVisits = $database->single();

$database->query("SELECT User_ID , COUNT(  `User_ID` ) c FROM interaction GROUP BY  `User_ID` ORDER BY c ASC LIMIT 1 ");
$leastVisits = $database->single();



$database->query("SELECT Exhibit_Title FROM exhibit where Exhibit_ID = ".$latestID);
$exhibits = $database->single();

$database->query("SELECT count(Inter_ID) as daily FROM interaction WHERE DATE(Inter_Time) = CURDATE( )");
$dailyCount = $database->single();
/*$date = date('Y-m-d H:i:s',time()-(7*86400));*/
$database->query("SELECT  count(Inter_ID) as weekly FROM interaction WHERE Inter_Time BETWEEN DATE_SUB( NOW( ) , INTERVAL 1 WEEK ) AND NOW( )");
$weeklyCount = $database->single();

$database->query("SELECT  count(Inter_ID) as monthly FROM interaction WHERE Inter_Time BETWEEN DATE_SUB( NOW( ) , INTERVAL 1 MONTH ) AND NOW( )");
$monthlyCount = $database->single();

$database->query("SELECT  count(Inter_ID) as yearly FROM interaction WHERE Inter_Time BETWEEN DATE_SUB( NOW( ) , INTERVAL 1 YEAR) AND NOW( )");
$yearlyCount = $database->single();

$database->query("SELECT count(DISTINCT User_ID) as uniqueUser from interaction");
$diffUsers = $database->single();

$database->query("SELECT count(*) as totalInter from interaction");

$output=array_merge($exhibits,$dailyCount,$weeklyCount,$monthlyCount,$avgStats,$yearlyCount,$totalInter,$diffUsers,$avgRating);


echo(json_encode($output));
?>