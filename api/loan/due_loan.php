<?php
header('Access-Control-ALlow-Origin: *');
header('Content-Type: application/json');

include("../../config/connect.php");


$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM loan_info WHERE pay_date <= '$date_now'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo json_encode (array('message' => 'No data found yet!.....Check back later!!'));
}
else{
    $due = array();
    $due['data'] = array();
while($row = mysqli_fetch_assoc($select))
{
$id = $row['id'];
$borrower = $row['borrower'];
$status = $row['status'];
$upstatus = $row['upstatus'];
$selectin = mysqli_query($link, "SELECT fname, lname FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
$geth = mysqli_fetch_array($selectin);
$name = $geth['fname'];

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$rowsys = mysqli_fetch_array($systemset);
$row['fullname'] = array();
array_push($row['fullname'], array("name" => $name, "currency" => $rowsys['currency']));
array_push($due['data'], $row);
}
echo json_encode($due);
}
?> 

