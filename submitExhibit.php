<?php
/*header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");*/

$data = json_decode(file_get_contents("php://input"));
$title = mysql_real_escape_string($data->title);
$alt = mysql_real_escape_string($data->altTitle);
$artist = mysql_real_escape_string($data->artist);
$date = mysql_real_escape_string($data->date);
$tag = mysql_real_escape_string($data->tag);
 
$con = mysql_connect("localhost", "root", "");
mysql_select_db('InfoShow', $con);

$qry_em = 'select count(*) as cnt from exhibit where Exhibit_Tag ="' . $tag . '"';
$qry_res = mysql_query($qry_em);
$res = mysql_fetch_assoc($qry_res);
 
if ($res['cnt'] == 0) {
    $qry = 'INSERT INTO exhibit (Exhibit_Title,Exhibit_AltTitle,Exhibit_Artist,Exhibit_Date,Exhibit_Tag) values ("' . $title . '","' . $alt . '","' . $artist . '","' . $date . '","' . $tag . '")';
    $qry_res = mysql_query($qry);
    if ($qry_res) {
        $arr = array('msg' => "User Created Successfully!!!", 'error' => '');
        $jsn = json_encode($arr);
        print_r($jsn);
    } else {
        $arr = array('msg' => "", 'error' => 'Error In inserting record');
        $jsn = json_encode($arr);
        print_r($jsn);
    }
} else {
    $arr = array('msg' => "", 'error' => 'User Already exists with same email');
    $jsn = json_encode($arr);
    print_r($jsn);
}
?>