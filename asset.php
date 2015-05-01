

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include ('database.php');
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "infoshow");

if (isset($_GET['tag'])){
	$tag = $_GET['tag'];
}

$database = new Database();
$random =5;
$database->query("SELECT Exhibit_Title, Exhibit_AltTitle, Exhibit_Date, Artist_FName, Artist_LName, exhibit.Exhibit_ID  FROM exhibit, asset, artist WHERE exhibit.Exhibit_ID = ".$tag." AND exhibit.Exhibit_Artist = artist.Artist_ID");
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

// Get Image from google
$imagePath= file_get_contents("http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".$exhibit."&userip=0.0.0.0");
$imageTextOut =json_decode($imagePath);
$imageArray = json_decode(json_encode($imageTextOut), true);

function array_value_recursive($key, array $arr){
    $val = array();
    array_walk_recursive($arr, function($v, $k) use($key, &$val){
        if($k == $key) array_push($val, $v);
    });
    return count($val) > 1 ? $val : array_pop($val);
}

$blurb = (array_value_recursive("extract", $blurbArray));
$bio = (array_value_recursive("extract", $bioArray));
$imageURL = (array_value_recursive("url", $imageArray)[1]);

$exhibits[0]["name"]=$fullName;
$exhibits[0]["blurb"]=$blurb;
$exhibits[0]["bio"]=$bio;
$exhibits[0]["image"] =$imageURL;
$exhibits[0]["date"] =$date;



/*$url = "https://ajax.googleapis.com/ajax/services/search/images?" .
       "v=1.0&q=barack%20obama&userip=54.229.149.217";

// sendRequest
// note how referer is set manually
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, "172.31.40.70");
$body = curl_exec($ch);
curl_close($ch);

$exhibits[0]["test"] =$body;*/

if(is_array($exhibits)){
 $outp = json_encode($exhibits,true);
}

echo($outp);



?>