


<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'database.php';
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "infoshow");

$database = new Database();

//Get total number of exhibits
$database->query("SELECT Exhibit_Title, Exhibit_ID FROM exhibit");
$exhibits = $database->resultSet();

//5 most popular exhibits
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

if(is_array($rows)){
 $out = json_encode($rows);
}

//Prep data component of chart

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
    /*"title"=> "Top 5 Exhibits by interaction",*/
    "displayExactValues"=> true,
    /*"width"=> 400,*/
    "height"=> 300,
    "is3D"=> true,
    "animation"=>[
        "startup"=>true,
        "duration"=> 1000,
        "easing"=> 'out'
      ],
      "tooltip"=> [
        "showColorCode"=> true
  
      ],
      "titleTextStyle"=> [
        "fontSize"=> 20,
        "color"=> 'grey'
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
);
// Return in JSON format for AngularJS to bind
echo(json_encode($chartData));
?>