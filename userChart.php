


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
$database->query('SELECT interaction.User_ID, user.User_FName,user.User_LName,
   	COUNT(interaction.User_ID) AS popularUsers 
    from interaction, user
    WHERE interaction.User_ID = user.User_ID
    GROUP BY interaction.User_ID
    ORDER BY popularUsers   DESC
    LIMIT    5;');
$rows = $database->resultSet();

$first =$rows[0]["User_FName"] ." ". $rows[0]["User_LName"];
$firstCount = intval($rows[0]["popularUsers"]);
$second = $rows[1]["User_FName"] ." ". $rows[1]["User_LName"];
$secondCount = intval($rows[1]["popularUsers"]);
$third = $rows[2]["User_FName"] ." ". $rows[2]["User_LName"];
$thirdCount = intval($rows[2]["popularUsers"]);
$fourth = $rows[3]["User_FName"] ." ". $rows[2]["User_LName"];
$fourthCount = intval($rows[3]["popularUsers"]);
$fifth = $rows[4]["User_FName"] ." ". $rows[2]["User_LName"];
$fifthCount = intval($rows[4]["popularUsers"]);

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