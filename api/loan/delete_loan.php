<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization,X-Requested-With');

include("../../config/connect.php");

$data= json_decode(file_get_contents("php://input"));


$id = mysqli_real_escape_string($link, $data->id);
						
						if($id == ''){
						echo  json_encode(array("message"=>"Row Not Selected!!!"));	
						}
							else{
							for($i=0; $i < $N; $i++)
							{
							    $search_loan_by_id = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id[$i]'");
    							$getloan_lid = mysqli_fetch_object($search_loan_by_id);
    						    $get_lid = $getloan_lid->lid;
						    
								$result = mysqli_query($link,"DELETE FROM loan_info WHERE id ='$id[$i]'");
								$result = mysqli_query($link,"DELETE FROM pay_schedule WHERE get_id ='$id[$i]'");
								$result = mysqli_query($link,"DELETE FROM payment_schedule WHERE lid ='$get_lid'");
								$result = mysqli_query($link,"DELETE FROM interest_calculator WHERE lid ='$get_lid'");
								$result = mysqli_query($link,"DELETE FROM attachment WHERE get_id ='$id[$i]'");
								$result = mysqli_query($link,"DELETE FROM battachment WHERE get_id ='$id[$i]'");
								$result = mysqli_query($link,"DELETE FROM additional_fees WHERE get_id ='$id[$i]'");
								$result = mysqli_query($link,"DELETE FROM collateral WHERE idm ='$id[$i]'");

								echo json_encode(array("message"=>"Row Delete Successfully!!!"));}
							}
							
?>				


	
</div>	
</div>
</div>	
</div>