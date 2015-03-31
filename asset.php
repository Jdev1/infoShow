<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include ('database.php');
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "infoshow");

$database = new Database();
$random =5;
$database->query("SELECT Exhibit_Title, Exhibit_AltTitle, Exhibit_Date, Artist_FName, Artist_LName, exhibit.Exhibit_ID, Asset_Path FROM exhibit, asset, artist WHERE exhibit.Exhibit_ID = 1 AND exhibit.Exhibit_Artist = artist.Artist_ID");
$exhibits = $database->resultSet();

//Prep Data
$date= substr($exhibits[0]["Exhibit_Date"], 0,4); 
$fullName =($exhibits[0]["Artist_FName"]." ". $exhibits[0]["Artist_LName"]);

//GET Blurb from wikipedia
$exhibit= $exhibits[0]["Exhibit_Title"];
$exhibit = str_replace(' ', '_', $exhibit);
$exhibitpath = "http://en.wikipedia.org/w/api.php?action=query&prop=extracts|info&exintro&titles=".$exhibit."&format=json&explaintext&redirects&inprop=url";

$text = file_get_contents($exhibitpath);
$textOut =json_decode($text);
$blurbArray = json_decode(json_encode($textOut), true);

//GET Bio from wikipedia
$fullNameB = str_replace(' ', '_', $fullName);
$bioText = file_get_contents("http://en.wikipedia.org/w/api.php?action=query&prop=extracts|info&exintro&titles=".$fullNameB."&format=json&explaintext&redirects&inprop=url");
$bioTextOut =json_decode($bioText);
$bioArray = json_decode(json_encode($bioTextOut), true);

// Get Picture from google



function array_value_recursive($key, array $arr){
    $val = array();
    array_walk_recursive($arr, function($v, $k) use($key, &$val){
        if($k == $key) array_push($val, $v);
    });
    return count($val) > 1 ? $val : array_pop($val);
}

$blurb = (array_value_recursive("extract", $blurbArray));
$bio = (array_value_recursive("extract", $bioArray));

//Get Number of Exhibits
/*$database->query('SELECT * from exhibit');
$rows = $database->resultSet();
$exhibitCount = $database->rowCount();*/
/*$random = rand( 1 , 16 );*/
$exhibits[0]["name"]=$fullName;
$exhibits[0]["blurb"]=$blurb;
$exhibits[0]["bio"]=$bio;
$exhibits[0]["alt"]=$exhibits[0]["Exhibit_AltTitle"];
$exhibits[0]["date"] =$date;
/*$exhibits["blurb"]=$blurb;*/
/*array_push($exhibits,$blurb);*/
/*$database->query("SELECT Asset_Path, FROM asset WHERE Exhibit_ID = 5");
$picPath = $database->resultSet();*/

/*$result = array_merge($exhibits,$picPath);*/

if(is_array($exhibits)){
 $outp = json_encode($exhibits,true);
}

echo($outp);

?>