<?php
header('Access-Control-ALlow-Origin: *');
header('Content-Type: application/json');

include("../../config/connect.php");


$select = mysqli_query($link, "SELECT * FROM borrowers ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
return json_encode(array("message" => "No data found yet!.....Check back later!!")) ;
}
else{
    $borrow = array();
    $borrow['data'] = array();
while($row = mysqli_fetch_assoc($select))
{
$id = $row['id'];
$acctno = $row['account'];
$lname = $row['lname'];
$fname = $row['fname'];
$email = $row['email'];
$posts = $row['posts'];
$acct_status = $row['acct_status'];
$bal = $row['balance'];
$rtype = $row['rtype'];
$gid = $row['gname'];
$fd_amount = $row['fd_amount'];
$fd_balance = $row['fd_balance'];

$search_group = mysqli_query($link, "SELECT * FROM lgroup_setup WHERE id = '$gid'");
$fetch_group = mysqli_fetch_assoc($search_group);
//$image = $row['image'];
$query = mysqli_query($link, "SELECT * FROM systemset");
$get_query = mysqli_fetch_array($query);

$search_fd = mysqli_query($link, "SELECT SUM(amount), SUM(profit) FROM fd_registration WHERE acn = '$acctno'");
$fetch_fd = mysqli_fetch_assoc($search_fd);
$fd_amt = $fetch_fd['SUM(amount)'];
$fd_profit = $fetch_fd['SUM(profit)'];
$fd_profit_minus_fdamt = $fd_profit - $fd_amt;
$row['customer'] =  array("currency" => $get_query['currency']);
array_push($borrow['data'], $row);
}
echo json_encode($borrow);
}
?>