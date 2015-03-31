<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "infoshow");

$result = $conn->query("SELECT User_FName, User_LName, User_EMail,Member_since, Last_active,User_ID, User_DOB FROM user");

$outp = "[";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "[") {$outp .= ",";}
    $outp .= '{"first_name":"'  . $rs["User_FName"] . '",';
    $outp .= '"last_name":"'   . $rs["User_LName"]        . '",';
    $outp .= '"EMail":"'   . $rs["User_EMail"]        . '",';
    $outp .= '"Member_Since":"'   . $rs["Member_since"]        . '",';
    $outp .= '"Last_active":"'   . $rs["Last_active"]        . '",';
    $outp .= '"User_ID":"'   . $rs["User_ID"]        . '",';
    $outp .= '"DOB":"'. $rs["User_DOB"]     . '"}';

}
$outp .="]";

$conn->close();

echo($outp);
?>