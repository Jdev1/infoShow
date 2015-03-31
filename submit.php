<?php
/*header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");*/

$data = json_decode(file_get_contents("php://input"));
$usrname = mysql_real_escape_string($data->uname);
$upswd = mysql_real_escape_string($data->pswd);
$uemail = mysql_real_escape_string($data->email);
 
$con = mysql_connect("localhost", "root", "");
mysql_select_db('InfoShow', $con);

$qry_em = 'select count(*) as cnt from user where User_EMail ="' . $uemail . '"';
$qry_res = mysql_query($qry_em);
$res = mysql_fetch_assoc($qry_res);
 
if ($res['cnt'] == 0) {
    $qry = 'INSERT INTO user (User_LName,User_Pass,User_EMail) values ("' . $usrname . '","' . $upswd . '","' . $uemail . '")';
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