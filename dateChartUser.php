


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
//ARRAY TO HOLD AVERAGE RATINGS FOR EACH EXHIBIT

/*$database->query('SELECT count(*) as total FROM interaction');
$result = $database->single();

$total = intval($result['total']);*/




$database->query('SELECT COUNT( Inter_Time ) as num , CAST( Inter_Time AS DATE ) AS iDate
FROM interaction
GROUP BY iDate
');
$rows = $database->resultSet();
$total = count($rows);

//ARRAY TO HOLD DATA
//$data =Array();
for ($i=0; $i < $total; $i++) { 

 

  $data[$i][0]=($rows[$i]["iDate"]);
  $data[$i][1]=intval($rows[$i]["num"]);
  
}

/*for ($i=1; $i <13 ; $i++) { 
  $database->query('SELECT avg(rating) as avgRating FROM interaction WHERE Exhibit_ID ='.$i.'');
  $result = $database->single();

 
}*/



$chartData = array(
  "type" => "ComboChart",
  "data"=> [
    /* */

  ],
  "options"=> [
    /*"title"=> "Daily Interactions",*/
    "displayExactValues"=> true,
    /*"width"=> 1000,*/
    "height"=> 400,
    "is3D"=> true,
    "colorAxis" => [
        "colors"=>["white","darkBlue"]
    ],
    "hAxis" => [
        "title"=>"Date",
        "textPosition"=> "none",
        "gridlines"=>["color"=>"white"],
    ],
    /*"sizeAxis" => [
        "title"=>"Exhibit",
        "minValue"=>0,
        "maxSize"=>20,
    ],*/
    "vAxis" => [
        "title"=>"Users",
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
    /*"chartArea"=> [
      "left"=> 50,
      "width"=>"100%",
      "height"=>"100%"
      
      
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
$chartData['data'][0]=[
      "Date",
      "Unique Users"
];
/*$chartData['data'][1]=$data[$i-1];*/


for ($i=1; $i < $total; $i++) { 
  $chartData['data'][$i]=$data[$i-1];
}
/*$chartData['data'][1]=["balls",1,2,"balls",4];
$chartData['data'][2]=["bells",5,6,"balls",15];*/

echo(json_encode($chartData));
?>