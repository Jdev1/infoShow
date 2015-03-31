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
$database->query("SELECT Exhibit_Title, exhibit.Exhibit_ID, Asset_Path FROM exhibit, asset WHERE exhibit.Exhibit_ID = 5");
$exhibits = $database->resultSet();


$artistPath=


$text = file_get_contents("http://en.wikipedia.org/w/api.php?action=query&prop=extracts|info&exintro&titles=the_thinker&format=json&explaintext&redirects&inprop=url");
$textOut =json_decode($text);
$blurbArray = json_decode(json_encode($textOut), true);

$bioText = file_get_contents("http://en.wikipedia.org/w/api.php?action=query&prop=extracts|info&exintro&titles=rodin&format=json&explaintext&redirects&inprop=url");
$bioTextOut =json_decode($bioText);
$bioArray = json_decode(json_encode($textOut), true);

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

$exhibits[0]["blurb"]=$blurb;
$exhibits[0]["bio"]=$bio;

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