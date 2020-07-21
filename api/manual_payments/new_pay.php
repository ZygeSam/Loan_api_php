<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Content-Type:  application/x-www-form-urlencoded');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization,X-Requested-With');

include("../../config/connect.php");

$data= json_decode(file_get_contents("php://input"));


function myreference($limit)
{
	return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}
$tid = $_SESSION['tid'];
$name = mysqli_real_escape_string($link, $data['teller']);
$lid =  $data['acte'];
$refid = myreference(10);
$loan_bal = mysqli_real_escape_string($link, $data['loan']);
$pay_date = mysqli_real_escape_string($link, $data['pay_date']);
$amount_to_pay = mysqli_real_escape_string($link, $data['amount_to_pay']);
$remarks = mysqli_real_escape_string($link, $data['remarks']);

$account_no = mysqli_real_escape_string($link, $data['account']);
$searchin = mysqli_query($link, "SELECT * FROM borrowers WHERE account ='$account_no'");
$get_searchin = mysqli_fetch_array($searchin);
$customer = $get_searchin['fname'].'&nbsp;'.$get_searchin['lname'];

$search4 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE id = '$loan_bal' AND status = 'UNPAID' ORDER BY id ASC") or die (mysqli_error($link));
$get_search4 = mysqli_fetch_array($search4);
$original_bal = $get_search4['balance'];

if($original_bal == "0")
{
    $update = mysqli_query($link, "UPDATE loan_info SET balance = '$original_bal', p_status = 'PAID' WHERE lid = '$lid'") or die (mysqli_error($link));
    $insert = mysqli_query($link, "INSERT INTO payments VALUES(null,'$tid','$lid','$refid','$account_no','$customer','$original_bal','$pay_date','$amount_to_pay','paid','')") or die (mysqli_error($link));
    $update = mysqli_query($link, "UPDATE pay_schedule SET status = 'PAID' WHERE id = '$loan_bal'") or die (mysqli_error($link));
    if(!($update && $insert))
    {
    echo '<meta http-equiv="refresh" content="2;url=newpayments.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
    echo '<br>';
    echo'<span class="itext" style="color: #FF0000">Unable to payment records.....Please try again later!</span>';
    }
    else{
    echo '<meta http-equiv="refresh" content="2;url=listpayment.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
    echo '<br>';
    echo'<span class="itext" style="color: #FF0000">Saving Payment.....Please Wait!</span>';
    }
}
else{
    $update = mysqli_query($link, "UPDATE loan_info SET balance = '$original_bal', p_status = 'PART-PAID' WHERE lid = '$lid'") or die (mysqli_error($link));
    $insert = mysqli_query($link, "INSERT INTO payments VALUES(null,'$tid','$lid','$refid','$account_no','$customer','$original_bal','$pay_date','$amount_to_pay','paid','')") or die (mysqli_error($link));
    $update = mysqli_query($link, "UPDATE pay_schedule SET status = 'PAID' WHERE id = '$loan_bal'") or die (mysqli_error($link));
    if(!($update && $insert))
    {
    echo '<meta http-equiv="refresh" content="2;url=newpayments.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
    echo '<br>';
    echo'<span class="itext" style="color: #FF0000">Unable to payment records.....Please try again later!</span>';
    }
    else{
    echo '<meta http-equiv="refresh" content="2;url=listpayment.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
    echo '<br>';
    echo'<span class="itext" style="color: #FF0000">Saving Payment.....Please Wait!</span>';
    }
}

?>
</div>
</body>
</html>