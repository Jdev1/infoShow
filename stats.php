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

$database->query("SELECT  Exhibit_ID FROM interaction ORDER BY  Inter_Time DESC LIMIT 1");
$latest = $database->single();
$latestID = $latest["Exhibit_ID"];

$database->query("SELECT Exhibit_Title FROM exhibit where Exhibit_ID = ".$latestID);
$exhibits = $database->single();

$database->query("SELECT count(Inter_ID) as daily FROM interaction WHERE DATE(Inter_Time) = CURDATE( )");
$dailyCount = $database->single();
/*$date = date('Y-m-d H:i:s',time()-(7*86400));*/
$database->query("SELECT  count(Inter_ID) as weekly FROM interaction WHERE Inter_Time BETWEEN DATE_SUB( NOW( ) , INTERVAL 1 WEEK ) AND NOW( )");
$weeklyCount = $database->single();

$database->query("SELECT  count(Inter_ID) as monthly FROM interaction WHERE Inter_Time BETWEEN DATE_SUB( NOW( ) , INTERVAL 1 MONTH ) AND NOW( )");
$monthlyCount = $database->single();

$database->query("SELECT  count(Inter_ID) as yearly FROM interaction WHERE Inter_Time BETWEEN DATE_SUB( NOW( ) , INTERVAL 1 year) AND NOW( )");
$yearlyCount = $database->single();

$database->query("SELECT count(DISTINCT User_ID) as uniqueUser from interaction");
$diffUsers = $database->single();

$database->query("SELECT count(*) as totalInter from interaction");
$totalInter = $database->single();

$database->query("SELECT avg(rating) as avgRat from interaction");
$avgRating = $database->single();

$database->query("SELECT YEAR(  `Inter_Time` ) as year , COUNT( * ) as cnt
FROM interaction
GROUP BY EXTRACT( YEAR
FROM  `Inter_Time` )");
$yearCount = $database->resultset();




$database->query("SELECT AVG( Visits ) AS AverageVis, AVG( UniqueVisits ) AS AverageUnique FROM (
	SELECT COUNT(  `User_ID` ) AS Visits, COUNT( DISTINCT  `User_ID` ) AS UniqueVisits, DATEDIFF( NOW( ) ,  `Inter_Time` ) AS 
	DAY 
	FROM interaction
	WHERE  `Inter_Time` > NOW( ) -30
	GROUP BY DATEDIFF( NOW( ) ,  `Inter_Time` )
)sub");
$avgStats = $database->resultset();
$avgStats['day'] = $avgStats[0]['AverageVis'];
$avgStats['month'] = $avgStats[0]['AverageVis'] *30;
$avgStats['year'] = $avgStats['month'] *12;

/*$counters = array("Daily"=>$dailyCount, "Weekly"=>$weeklyCount, "Yearly"=>$yearCount);*/
$output=array_merge($exhibits,$dailyCount,$weeklyCount,$monthlyCount,$avgStats,$yearlyCount,$totalInter,$diffUsers,$avgRating);


echo(json_encode($output));
?>