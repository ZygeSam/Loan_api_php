<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization,X-Requested-With');

include("../config/connect.php");

$data= json_decode(file_get_contents("php://input"));



$reg_type = mysqli_real_escape_string($link, $data->reg_type);
$gname =  mysqli_real_escape_string($link, $data->gname);
$g_position =  mysqli_real_escape_string($link, $data->g_position);
$title =  mysqli_real_escape_string($link, $data->title);
$fname =  mysqli_real_escape_string($link, $data->fname);
$lname = mysqli_real_escape_string($link, $data->lname);
$email = mysqli_real_escape_string($link, $data->email);
$phone = mysqli_real_escape_string($link, $data->phone);

$gender =  mysqli_real_escape_string($link, $data->gender);
$dob =  mysqli_real_escape_string($link, $data->bdate);
$wstatus = mysqli_real_escape_string($link, $data->wstatus);
$unumber = mysqli_real_escape_string($link, $data->unumber);
$bizname = mysqli_real_escape_string($link, $data->bizname);

$addrs1 = mysqli_real_escape_string($link, $data->addrs1);
$addrs2 = mysqli_real_escape_string($link, $data->addrs2);
$city = mysqli_real_escape_string($link, $data->city);
$state = mysqli_real_escape_string($link, $data->state);
$zip = mysqli_real_escape_string($link, $data->zip);
$country = mysqli_real_escape_string($link, $data->country);
$account = mysqli_real_escape_string($link, $data->account);
$status = "Completed";
$lofficer = mysqli_real_escape_string($link, $data->lofficer);
$nok_name = mysqli_real_escape_string($link, $data->nok_name);
$nok_rela = mysqli_real_escape_string($link, $data->nok_rela);
$nok_contact = mysqli_real_escape_string($link, $data->nok_contact);
$nok_addrs = mysqli_real_escape_string($link, $data->nok_addrs);
$nok_city = mysqli_real_escape_string($link, $data->nok_city);
$nok_town = mysqli_real_escape_string($link, $data->nok_town);
//$branchid = mysqli_real_escape_string($link, $data->branch);

$date_time = mysqli_real_escape_string($link, $data->date_time);
$reg_date = date('Y-m-d h:m:s', strtotime($date_time));

$verify_email = mysqli_query($link, "SELECT * FROM borrowers WHERE email = '$email'");
$detect_email = mysqli_num_rows($verify_email);

$search_user = mysqli_query($link, "SELECT * FROM user WHERE userid = '$lofficer'");
$get_user = mysqli_fetch_array($search_user);
$branchid = $get_user['branchid'];

$verify_groupmember_limit = mysqli_query($link, "SELECT * FROM lgroup_setup WHERE id = '$gname'");
$fetch_vg = mysqli_fetch_array($verify_groupmember_limit);
$max_member = $fetch_vg['max_member'];

$verify_memgroup = mysqli_query($link, "SELECT * FROM borrowers WHERE rtype = '$reg_type' AND gname = '$gname'");
$v_memgroup = mysqli_num_rows($verify_memgroup);

$target_dir = "../img/";
$target_file = $target_dir.basename($data["image"]["name"]);
$target_file_c_sign = $target_dir.basename($data["c_sign"]["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$imageFileType_c_sign = pathinfo($target_file_c_sign,PATHINFO_EXTENSION);
$check = getimagesize($data["image"]["tmp_name"]);
$check_c_sign = getimagesize($data["c_sign"]["tmp_name"]);

if($detect_email == 1){
return json_encode(array("message" => "Sorry, Email Address has already been used by someone else"));
}
elseif($reg_type == "Group" && $max_member == $v_memgroup){
return json_decode(array("message" =>"The Group has already reach the maximum limit of member's.</p>"));
}
else{
$sourcepath = $data->image["tmp_name"];
$sourcepath_c_sign = $data->c_sign["tmp_name"];
$targetpath = "../img/" . $data->image["name"];
$targetpath_c_sign = "../img/" . $data->c_sign["name"];
move_uploaded_file($sourcepath,$targetpath);
move_uploaded_file($sourcepath_c_sign,$targetpath_c_sign);

$location = "img/".$data->image['name'];
$loaction_c_sign = "img/".$data->c_sign['name'];

//$today = date("Y-m-d");
$insert = mysqli_query($link, "INSERT INTO borrowers VALUES(null,'$title','$fname','$lname','$email','$phone','$gender','$dob','$wstatus','$unumber','$bizname','$addrs1','$addrs2','$city','$state','$zip','$country','','0','$account','0.0','$location','$reg_date','0000-00-00','$status','$lofficer','$loaction_c_sign','$branchid','Activated','','$nok_name','$nok_rela','$nok_contact','$nok_addrs','$nok_city','$nok_town','$reg_type','$gname','$g_position','0.0','0.0','No')") or die (mysqli_error($link));

if($insert)
{
   $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
   $r = mysqli_fetch_object($query);
   $sysabb = $r->abb;
       
   $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated'");
   $fetch_gateway = mysqli_fetch_object($search_gateway);
   $gateway_uname = $fetch_gateway->username;
   $gateway_pass = $fetch_gateway->password;
   $gateway_api = $fetch_gateway->api;

   $sms = "$sysabb>>>Welcome $fname! Your Username is: $account, Password is: $lname. Thanks.";

   include('../../config/send_general_sms.php');
   
   echo json_encode(array("message" => "New Customer Added Successfully!...Login Details has been sent to Customer's Phone Number!!"));
}
else{
   echo json_encode(array("message" => "Unable to Register...Please Try Again Later!!"));
}
}
?>     