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
$database->query('SELECT * from exhibit');
$rows = $database->resultSet();
$exhibitCount = $database->rowCount();
//ARRAY TO HOLD AVERAGE RATINGS FOR EACH EXHIBIT

/*for ($i=1; $i <13 ; $i++) { 
  $database->query('SELECT avg(rating) as avgRating FROM interaction WHERE Exhibit_ID ='.$i.'');
  $result = $database->single();

  $data[$i-1][3] =  intval($result['avgRating']);
}*/



$database->query('SELECT interaction.Exhibit_ID,Inter_Time, Exhibit_Title,
   	COUNT(interaction.Exhibit_ID) AS popular 
    from interaction, exhibit
    WHERE interaction.Exhibit_ID = exhibit.Exhibit_ID 
    GROUP BY interaction.Exhibit_ID
    ORDER BY popular  DESC
    LIMIT    12; ');

$rows = $database->resultSet();


//ARRAY TO HOLD DATA
//$data =Array();
for ($i=0; $i < 12; $i++) { 

 

  /*$data[$i][0]=$rows[$i]["Exhibit_Title"];
  $data[$i][1]=intval($rows[$i]["Exhibit_ID"]);
  $data[$i][2]=intval($rows[$i]["popular"]);*/
  //$data[$i][3]=5;
}

for ($i=1; $i <13 ; $i++) { 
  $database->query('SELECT avg(rating) as avgRating FROM interaction WHERE Exhibit_ID ='.$i.'');
  $result = $database->single();
  /*$data[$i-1][3] =  intval($result['avgRating']);
  $data[$i-1][4] =  intval($result['avgRating']);*/
 
}
$date=date_create("2013-03-15");
$jsDate = date_format($date,"Y/m/d ");


$chartData = array(
  "type" => "Calendar",
  "data"=> [
    [
      "Date",
      "Average Rating"
    ],
    [
      $jsDate,
      30
      
    ],

  ],
  "options"=> [
    "title"=> "Interaction Breakdown",
    "displayExactValues"=> true,
    /*"width"=> 1000,*/
    "height"=> 400,
    "is3D"=> true,
    "colorAxis" => [
        "colors"=>["white","blue","darkBlue"]
    ],
    "hAxis" => [
        "title"=>"Exhibit",
        "minValue"=>0,
        "maxValue"=>20,
        "gridlines"=>["color"=>"white"],
    ],
    /*"sizeAxis" => [
        "title"=>"Exhibit",
        "minValue"=>0,
        "maxSize"=>20,
    ],*/
    "vAxis" => [
        "title"=>"Visits",
        "minValue"=>0,
        "maxValue"=>20,
    ],
    "bubble" => [
        "textStyle"=>["fontSize"=>15]
    ],
    "animation"=>[
        "startup"=>true,
        "duration"=> 3000,
        "easing"=> 'out'
      ],
/*    "chartArea"=> [
      "left"=> 10,
      "top"=> 10,
      "bottom"=> 0
      
    ]*/
  ],
  "formatters"=> [
    "number"=> [
      [
        "columnNum"=> 3,
        "pattern"=> ""
      ]
    ]
  ],
  "displayed"=> true
)
	;
/*$chartData['data'][0]=[
      "Date",
      "Average Rating"
    ];
for ($i=1; $i < 13; $i++) { 
  $chartData['data'][$i]=$data[$i-1];
}*/
/*$chartData['data'][1]=["balls",1,2,"balls",4];
$chartData['data'][2]=["bells",5,6,"balls",15];*/

echo(json_encode($chartData));
?>