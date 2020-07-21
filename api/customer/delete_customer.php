<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization,X-Requested-With');

include("../../config/connect.php");

$data= json_decode(file_get_contents("php://input"));


$id = mysqli_real_escape_string($link, $data->id);
 $delete = mysqli_query($link, " DELETE FROM borrowers WHERE id =  {$id} ") or die (mysqli_error($link) . " " . mysqli_error_list($link));

if($delete)
 {
  
   
    echo json_encode(array("message" => "Customer Deleted Successfully!..."));
 }
 else{
    echo json_encode(array("message" => "Unable to Delete...Please Try Again Later!!"));
 }
?>     