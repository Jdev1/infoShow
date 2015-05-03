<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$data = json_decode(file_get_contents("php://input"));

include ('database.php');
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "infoshow");



$rating = ($data->rating);
$id = mysql_real_escape_string($data->tag);
echo $rating;

/*$database = new Database();
$database->query('INSERT INTO users (FName, LName, Age, Gender) VALUES (:fname, :lname, :age, :gender)');
$database->bind(':fname', 'John');
$database->bind(':lname', 'Smith');
$database->bind(':age', '24');
$database->bind(':gender', 'male');
$database->execute();

/*This code is just to check it worked*/

/*$database->query('SELECT * FROM mytable WHERE ID= :Userid');
$database->bind(':Userid',$UserID);
$rows = $database->resultset();
echo '< p r e >'; print_r($rows); echo "< / p r e >"*/


?>