<?php
header('Access-Control-ALlow-Origin: *');
header('Content-Type: application/json');

include("../../config/connect.php");

$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM pay_schedule WHERE status = 'UNPAID'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
    echo json_encode(array("message" => "No data found yet!.....Check back later!!"));
}
else{
    $out = array();
    $out['data'] = array();
while($row = mysqli_fetch_assoc($select))
{
$id = $row['id'];

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$rowsys = mysqli_fetch_assoc($systemset);
$row['currency'] = array();
array_push($row['currency'], $rowsys['currency']);
array_push($out['data'], $row);
} 
    echo json_encode($out);
}
         
?>