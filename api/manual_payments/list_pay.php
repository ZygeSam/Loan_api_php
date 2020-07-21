<?php
header('Access-Control-ALlow-Origin: *');
header('Content-Type: application/json');

include("../../config/connect.php");


//$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM payments") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo  json_encode(array("message" => "No data found yet!.....Check back later!!"));
}
else{
    $pay = array();
    $pay['data'] = array();
while($row = mysqli_fetch_assoc($select))
{
$id = $row['id'];
$refid = $row['refid'];
$lid = $row['lid'];
$tid = $row['tid'];
$account_no = $row['account_no'];
$remarks = $row['remarks'];
$customer = $row['customer'];
$loan_bal = $row['loan_bal'];
$amount_payed = $row['amount_to_pay'];

$getin = mysqli_query($link, "SELECT name FROM user WHERE id = '$tid'") or die (mysqli_error($link));
$have = mysqli_fetch_assoc($getin);
$nameit = $have['name'];

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_assoc($select1);
$currency = $row1['currency'];
$row['currency'] = array();
$row['user'] = array();
array_push($row['user'], $nameit);
array_push($row['currency'], $currency);
array_push($pay['data'], $row);
}
echo json_encode($pay);
}
?>    
