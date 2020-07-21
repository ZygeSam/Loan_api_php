<?php
header('Access-Control-ALlow-Origin: *');
header('Content-Type: application/json');

include("../../config/connect.php");


$select = mysqli_query($link, "SELECT * FROM loan_info") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo  json_encode(array("message" => "No data found yet!.....Check back later!!"));
}
else{
    $loan = array();
    $loan['data'] = array();
while($row = mysqli_fetch_assoc($select))
{
$id = $row['id'];
$lid = $row['lid'];
$borrower = $row['borrower'];
$acn = $row['baccount'];
$status = $row['status'];
$upstatus = $row['upstatus'];
$selectin = mysqli_query($link, "SELECT fname, lname FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
$geth = mysqli_fetch_assoc($selectin);
$name = $geth['fname'];
$lproduct = $row['lproduct'];

$search_product = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
$get_product = mysqli_fetch_object($search_product);
$pname = $get_product->pname;

//System settings
$systemset = mysqli_query($link, "SELECT * FROM systemset");
$rowsys = mysqli_fetch_array($systemset);
$row['product'] =array();
array_push($row['product'],  array("product_name" => $pname, "currency" => $rowsys['currency']));
array_push($loan['data'], $row);
}
echo json_encode($loan);
}
?> 