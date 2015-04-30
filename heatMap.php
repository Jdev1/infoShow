
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



$database->query('SELECT interaction.Exhibit_ID, Exhibit_Title,
   	COUNT(interaction.Exhibit_ID) AS popular 
    from interaction, exhibit
    WHERE interaction.Exhibit_ID = exhibit.Exhibit_ID 
    GROUP BY interaction.Exhibit_ID
    ORDER BY popular  DESC
    LIMIT    12; ');

$rows = $database->resultSet();

//ARRAY TO HOLD DATA
// Hard coded co-ordinated to represent static locations on floor diagram
$location =Array([2,3],[2,6],[6,3],[6,6],[7.5,1.5],[7.5,9],[12,1.5],[12,9],[17,3],[14,3],[14,6.3],[17,6.3]);


for ($i=0; $i < 12; $i++) { 

  $data[$i][0]=$rows[$i]["Exhibit_Title"];
  $data[$i][1]=($location[$i][0]*10);
  $data[$i][2]=($location[$i][1]*10);
  $data[$i][3] =  intval($rows[$i]["popular"]);
  $data[$i][4] =  intval($rows[$i]["popular"]);
  /*$data[$i][1]=intval($rows[$i]["Exhibit_ID"]);
  $data[$i][2]=intval($rows[$i]["popular"]);*/
  //$data[$i][3]=5;
}


$chartData = array(
  "type" => "BubbleChart",
  "data"=> [

  ],
  "options"=> [
    /*"title"=> "Interaction Breakdown",*/
    "displayExactValues"=> true,
    "backgroundColor"=>"none",
    /*"width"=> 1000,*/
    "height"=> 600,
    "is3D"=> true,
    "colorAxis" => [
        "colors"=>["Blue","green","yellow","red"],
        /*"legend"=>["position"=>"none"]*/
    ],
    "hAxis" => [
        /*"title"=>"Exhibit",*/
        "minValue"=>0,
        "maxValue"=>130,
        "textPosition"=>"none",
        "baselineColor"=>"none",
        "gridlines"=>["color"=>"none"]
    ],
    "sizeAxis" => [
        "title"=>"Exhibit",
        "minSize"=>10,
        "maxSize"=>40,
    ],
    "vAxis" => [
        /*"title"=>"Visits",*/
        "minValue"=>0,
        "maxValue"=>100,
        "gridlines"=>["color"=>"none"],
        "baselineColor"=>"none",
        "textPosition"=>"none"
    ],
    "bubble" => [
        "textStyle"=>["fontSize"=>15]
    ],
    /*"animation"=>[
        "startup"=>true,
        "duration"=> 3000,
        "easing"=> 'out'
      ],*/
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
$chartData['data'][0]=[
      "ExhibitName",
      "",
      "",
      "",
      "Visits"
    ];
for ($i=1; $i < 13; $i++) { 
  $chartData['data'][$i]=$data[$i-1];
}


echo(json_encode($chartData));
?>