<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'database.php';
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "infoshow");

$database = new Database();

$database->query('SELECT Inter_ID, User_ID, interaction.Exhibit_ID, rating, Inter_Time, Exhibit_Title, Artist_LName, Artist_FName, Artist_ID FROM interaction, exhibit, artist  where interaction.Exhibit_ID = exhibit.Exhibit_ID and exhibit.Exhibit_Artist = artist.Artist_ID');
/*$database->query("SELECT Exhibit_Title, Exhibit_AltTitle, Exhibit_Artist, Exhibit_Date, Exhibit_Tag FROM exhibit");*/
$rows = $database->resultSet();

//echo $database->rowCount();
if(is_array($rows)){
 $out = json_encode($rows);
}

echo $out;


?>