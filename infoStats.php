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

/*$database->query("SELECT  Exhibit_ID FROM interaction ORDER BY  Inter_Time DESC LIMIT 1");
$latest = $database->single();
$latestID = $latest["Exhibit_ID"];

$database->query("SELECT Exhibit_Title FROM exhibit where Exhibit_ID = ".$latestID);
$exhibits = $database->single();

$database->query("SELECT count(Inter_ID) as daily FROM interaction WHERE DATE(Inter_Time) = CURDATE( )");
$dailyCount = $database->single();

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
$yearCount = $database->resultset();*/




$database->query("SELECT User_ID, Exhibit_ID
FROM interaction
ORDER BY ABS(  `Inter_ID` ) DESC 
LIMIT 1");
$userLatest = $database->single();
$latestID = $userLatest["User_ID"];
$latestExID = $userLatest["Exhibit_ID"];

$database->query("SELECT Exhibit_Title FROM exhibit WHERE Exhibit_ID =".$latestExID);
$exhibitName = $database->single();

$database->query("SELECT * 
FROM user
where User_ID =".$latestID);
$userStats = $database->single();

$database->query("SELECT User_ID as most, count(User_ID) as count from interaction  group by User_ID order by count desc");
$userMost = $database->single();
$userMostID= $userMost["most"];

$database->query("SELECT User_FName as mostFName, User_LName as mostLName, member_since as mostJoin
FROM user
where User_ID =".$userMostID);
$userMostName = $database->single();
$date=$userMostName["mostJoin"];
$array=explode("-",$date);
$rev=array_reverse($array);
$date=implode("-",$rev);
$userMostName["mostJoin"]=$date;

$database->query("SELECT AVG(TIMESTAMPDIFF(YEAR, User_DOB, CURDATE())) AS `Average` FROM user WHERE User_DOB IS NOT NULL");
$userAvAge = $database->single();

$database->query("SELECT User_ID as HiRatingID, AVG(rating) as hiAvRating, count(rating) as HiInters
FROM interaction
GROUP BY User_ID
ORDER BY 2 DESC
LIMIT 1;");
$userHiAvRating = $database->single();
$HiAvID = $userHiAvRating["HiRatingID"];
$database->query("SELECT User_FName as HiAvgFName, User_LName as HiAvgtLName
FROM user
where User_ID =".$HiAvID );
$userHiAvgName = $database->single();


$database->query("SELECT User_ID as LoRatingID, AVG(rating) as loAvRating, count(rating) as loInters
FROM interaction
GROUP BY User_ID
ORDER BY 2 asc
LIMIT 1;");
$userLoAvRating = $database->single();
$LoAvID = $userLoAvRating["LoRatingID"];
$LoAvCount = $userLoAvRating["loInters"];
$database->query("SELECT User_FName as LoAvgFName, User_LName as LoAvgtLName
FROM user
where User_ID =".$LoAvID);
$userLoAvgName = $database->single();

//Average user stats
$database->query("SELECT AVG(TIMESTAMPDIFF(YEAR, User_DOB, CURDATE())) AS `Average` FROM user WHERE User_DOB IS NOT NULL");
$userAvAge = $database->single();

$database->query("SELECT avg(rating) as AVG_Rating from interaction ");
$userAVRating = $database->single();

$database->query("SELECT AVG(TIMESTAMPDIFF(YEAR, member_since, CURDATE())) AS `AverageJoin` FROM user WHERE member_since IS NOT NULL");
$userAvJoin = $database->single();


echo(json_encode(array_merge($userStats,$exhibitName,$userMost,$userMostName,$userAvAge,$userHiAvRating,$userLoAvRating,$userHiAvgName, $userLoAvgName, $userAVRating,$userAvJoin)));
?>