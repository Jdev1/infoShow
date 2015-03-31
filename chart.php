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
$exhibitCount = $database->rowCount();*/


$database->query("SELECT Exhibit_Title, Exhibit_ID FROM exhibit");
$exhibits = $database->resultSet();



$database->query('SELECT interaction.Exhibit_ID, Exhibit_Title,
   	COUNT(interaction.Exhibit_ID) AS popular 
    from interaction, exhibit
    WHERE interaction.Exhibit_ID = exhibit.Exhibit_ID 
    GROUP BY interaction.Exhibit_ID
    ORDER BY popular  DESC
    LIMIT    5; ');

$rows = $database->resultSet();

$first =$rows[0]["Exhibit_Title"];
$firstCount = intval($rows[0]["popular"]);
$second = $rows[1]["Exhibit_Title"];
$secondCount = intval($rows[1]["popular"]);
$third = $rows[2]["Exhibit_Title"];
$thirdCount = intval($rows[2]["popular"]);
$fourth = $rows[3]["Exhibit_Title"];
$fourthCount = intval($rows[3]["popular"]);
$fifth = $rows[4]["Exhibit_Title"];
$fifthCount = intval($rows[4]["popular"]);

//echo $database->rowCount();
if(is_array($rows)){
 $out = json_encode($rows);
}
/*if(isset($_GET["param"]))
	echo $out;*/

$chartData = array(
  "type" => "PieChart",
  "data"=> [
    [
      "Exhibit",
      "Visits"
    ],
    [
      $first,
      $firstCount
    ],
    [
      $second,
      $secondCount
    ],
    [
      $third,
      $thirdCount
    ],
    [
      $fourth,
      $fourthCount
    ],
    [
      $fifth,
      $fifthCount
    ]
  ],
  "options"=> [
    "title"=> "Top 5 Exhibits by interaction",
    "displayExactValues"=> true,
    /*"width"=> 400,*/
    "height"=> 260,
    "is3D"=> false,
    "animation"=>[
        "startup"=>true,
        "duration"=> 1000,
        "easing"=> 'out'
      ],
/*    "chartArea"=> [
      "left"=> 10,
      "top"=> 10,
      "bottom"=> 0,
      "height"=> "100%"
    ]*/
  ],
  "formatters"=> [
    "number"=> [
      [
        "columnNum"=> 1,
        "pattern"=> ""
      ]
    ]
  ],
  "displayed"=> true
)
	;


$file = file_get_contents('./dummyData/graphs2.txt', FILE_USE_INCLUDE_PATH);
$graphData = json_decode($file, true);
echo(json_encode($chartData));
?>