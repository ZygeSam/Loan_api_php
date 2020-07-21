<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Content-Type:  application/x-www-form-urlencoded');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization,X-Requested-With');

include("../../config/connect.php");

$data= json_decode(file_get_contents("php://input"));


$lproduct =  mysqli_real_escape_string($link, $data->lproduct);
$borrower =  mysqli_real_escape_string($link, $data->borrower);
$baccount = mysqli_real_escape_string($link, $data->account);
$bacinfo = mysqli_real_escape_string($link, $data->bacinfo);
$amount = mysqli_real_escape_string($link, $data->amount);
$income_amt = mysqli_real_escape_string($link, $data->income);
$salary_date = mysqli_real_escape_string($link, $data->salary_date);
$employer =  mysqli_real_escape_string($link, $data->employer);
//$date_release = mysqli_real_escape_string($link, $data->date_release);
$agent = mysqli_real_escape_string($link, $data->agent);
//$unumber = mysqli_real_escape_string($link, $data->unumber);
$gname = mysqli_real_escape_string($link, $data->g_name);
$gphone = mysqli_real_escape_string($link, $data->g_phone);
//$g_dob = mysqli_real_escape_string($link, $data->g_dob);
//$g_bname = mysqli_real_escape_string($link, $data->g_bname);

$g_rela = mysqli_real_escape_string($link, $data->grela);
$g_address = mysqli_real_escape_string($link, $data->gaddress);
$status = mysqli_real_escape_string($link, $data->status);
//$remarks = mysqli_real_escape_string($link, $data->remarks);
//$pay_date = mysqli_real_escape_string($link, $data->pay_date);

$gname1= mysqli_real_escape_string($link, $data->g_name1);
$gphone1 = mysqli_real_escape_string($link, $data->g_phone1);
//$g_dob = mysqli_real_escape_string($link, $data->g_dob);
//$g_bname = mysqli_real_escape_string($link, $data->g_bname);

$g_rela1 = mysqli_real_escape_string($link, $data->grela1);
$g_address1 = mysqli_real_escape_string($link, $data->gaddress1);

//$calc_int = ($interest / 100) * $amount;
//$amount_topay = $amount + $calc_int;
$upstatus = "Pending";
$teller = mysqli_real_escape_string($link, $data->teller);
$lreasons = mysqli_real_escape_string($link, $data->lreasons);

$search_interest = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
$get_interest = mysqli_fetch_object($search_interest);
$max_duration  = $get_interest->duration;
$interest = $get_interest->interest/100;
$tenor = $get_interest->tenor;

$amount_topay = ($amount * $interest) + $amount;

$target_dir = "../img/";
$target_file = $target_dir.basename($data->image["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$check = getimagesize($data->image["tmp_name"]);

// $id = $_SESSION['tid'];
// $lid = 'LID-'.rand(2000000,100000000);

$verify_outstandingloan = mysqli_query($link, "SELECT * FROM loan_info WHERE borrower = '$borrower'"); 
$fetch_loan = mysqli_fetch_array($verify_outstandingloan);
$p_status = $fetch_loan['p_status'];

$verify_group = mysqli_query($link, "SELECT * FROM lgroup_setup WHERE id = '$borrower'");
$fetch_group = mysqli_fetch_array($verify_group);
$group_name = $fetch_group['gname'];

if($check == false)
{
	echo json_encode( array("message" => "Invalid file type"));
}
elseif($data->image["size"] > 500000)
{
	echo json_encode( array("message" => "Image must not more than 500KB!"));
}
elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
{
    echo json_encode( array("message" => "Image must not more than 500KB!"));
}
elseif($p_status == "UNPAID" || $p_status == "PART-PAID")
{
    echo json_encode( array("message" => "Sorry, The Customer Still Have Few Outstanding Loan to be Balanced"));
}
else{
	$sourcepath = $data->image["tmp_name"];
	$targetpath = "../img/" . $data->image["name"];
	move_uploaded_file($sourcepath,$targetpath);
	
	$location = "img/".$data->image['name'];
	$p_status = "UNPAID";
	
	$pschedule = mysqli_real_escape_string($link, $data->pschedule);
	
	if($gname1 == "")
	{
	    $insert = mysqli_query($link, "INSERT INTO loan_info VALUES(null,'$lid','$lproduct','$borrower','$baccount','$income_amt','$salary_date','$employer','$bacinfo','$amount','','','$agent','','$gname','$gphone','$g_address','','$g_bname','$g_rela','$location','Pending','','','$interest','$amount_topay','$amount_topay','$teller','$lreasons','$upstatus','$p_status','')") or die ("Error: " . mysqli_error($link));
		$insert = mysqli_query($link, "INSERT INTO payment_schedule VALUES(null,'$lid','$baccount','$pschedule','$tenor','')") or die ("Error: " . mysqli_error($link));

		$search_id = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'") or die ("Error: " . mysqli_error($link));
		$fetch_id = mysqli_fetch_array($search_id);
		$get_id = $fetch_id['id'];
		
		include("../../alert_sender/loan_process1_alert.php");
        echo json_encode( array("message" => "Saving Loan Information.....2 more steps to complete the request"));
	}
	elseif($gname1 != "")
	{
	    $insert = mysqli_query($link, "INSERT INTO loan_info VALUES(null,'$lid','$lproduct','$borrower','$baccount','$income_amt','$salary_date','$employer','$bacinfo','$amount','','','$agent','','$gname','$gphone','$g_address','','$g_bname','$g_rela','$location','Pending','','','$interest','$amount_topay','$amount_topay','$teller','$lreasons','$upstatus','$p_status','')") or die ("Error: " . mysqli_error($link));
		$insert = mysqli_query($link, "INSERT INTO payment_schedule VALUES(null,'$lid','$baccount','$pschedule','$tenor','')") or die ("Error: " . mysqli_error($link));

		$search_id = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'") or die ("Error: " . mysqli_error($link));
		$fetch_id = mysqli_fetch_array($search_id);
		$get_id = $fetch_id['id'];
		
		include("alert_sender/loan_process1_alert.php");
		$insert = mysqli_query($link, "INSERT INTO other_guarantor VALUES(null,'$lid','$borrower','$gname1','$gphone1','$g_address1','$g_bname1','$g_rela1')") or die ("Error: " . mysqli_error($link));
		echo json_encode(array("message" => "Saving Loan Information.....2 more steps to complete the request"));
	}
}



?>