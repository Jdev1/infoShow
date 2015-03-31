<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "infoshow");

$result = $conn->query("SELECT Exhibit_Title, Exhibit_AltTitle, Exhibit_Artist, Exhibit_Date, Exhibit_Tag FROM exhibit");

$outp = "[";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "[") {$outp .= ",";}
    $outp .= '{"Title":"'  . $rs["Exhibit_Title"] . '",';
    $outp .= '"AltTitle":"'   . $rs["Exhibit_AltTitle"]        . '",';
    $outp .= '"Artist":"'   . $rs["Exhibit_Artist"]        . '",';
    $outp .= '"Date":"'   . $rs["Exhibit_Date"]        . '",';
    $outp .= '"Tag":"'   . $rs["Exhibit_Tag"]        . '"}';
     

}
$outp .="]";

$conn->close();

echo($outp);
?>