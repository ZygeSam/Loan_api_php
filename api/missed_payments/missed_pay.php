<?php
header('Access-Control-ALlow-Origin: *');
header('Content-Type: application/json');

include("../../config/connect.php");


$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo json_encode(array("message" => "No data found yet!.....Check back later!!"));
}
else{
    $missed = array();
    $missed['data'] = array();
    while($row = mysqli_fetch_assoc($select))
{
$id = $row['id'];
$status = $row['status'];
$lid = $row['lid'];

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$rowsys = mysqli_fetch_assoc($systemset);

$search_payment = mysqli_query($link, "SELECT * FROM authorized_card WHERE lid = '$lid'") or die ("Error:" . mysqli_error($link));
$reg_pay_query = mysqli_fetch_object($search_payment);
$row['pay'] = array();
array_push($row['pay'], array("currency" => $rowsys['currency'], "authorized_code" => $reg_pay_query->authorized_code ));
array_push($missed['data'], $row);
}
echo json_encode($missed);
}
?> 