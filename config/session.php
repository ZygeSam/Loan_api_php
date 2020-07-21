<?php 
error_reporting(0); 
include ('connect.php');
 session_start(); 
//Check whether the session variable SESS_MEMBER_ID is present or not
if (!isset($_SESSION['tid']) || (trim($_SESSION['tid']) == '')) { ?>
<script>
alert('You Are Not Logged In !! Please Login to access this page');
window.location = "../index.php";
</script>
<?php 
}
$session_id=$_SESSION['tid'];
$acnt_id=$_SESSION['acctno'];

$user_query = mysqli_query($link, "select * from user where id = '$session_id'")or die(mysqli_error());
$user_row = mysqli_fetch_array($user_query);
$name = $user_row['name'];
$email = $user_row['email'];
$branchid = $user_row['branchid'];

$branch_query = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branchid'")or die(mysqli_error($link));
$branch_row = mysqli_fetch_array($branch_query);
$bcurrency = $branch_row['currency'];
$c_rate = $branch_row['c_rate'];
$mybname = $branch_row['bname'];
$branch_addrs = $branch_row['branch_addrs'];
$branch_country = $branch_row['bcountry'];
$branch_province = $branch_row['branch_province'];
$min_loanamout = $branch_row['minloan_amount'];
$max_loanamount = $branch_row['maxloan_amount'];
$min_interest_rate = $branch_row['min_interest_rate'];
$max_interest_rate = $branch_row['max_interest_rate'];

$user_query2 = mysqli_query($link, "select * from borrowers where id = '$session_id'")or die(mysqli_error());
$user_row2 = mysqli_fetch_array($user_query2);
$bname = $user_row2['fname'].' '.$user_row2['lname'];
$email2 = $user_row2['email'];
$bbranchid = $user_row2['branchid'];

$check_module = mysqli_query($link, "SELECT * FROM staff_module_permission WHERE staff_tid = '".$_SESSION['tid']."'") or die ("Error" . mysqli_error($link));
$get_module = mysqli_fetch_array($check_module);
$list_all_customer = $get_module['all_customer'];
$del_customer = $get_module['del_customer'];
$new_customer = $get_module['new_customer'];
$borrower_list = $get_module['borrower_list'];
$send_sms = $get_module['send_sms'];
$send_email = $get_module['send_email'];
$add_to_borrower = $get_module['add_to_borrower'];
$update_info = $get_module['update_info'];
$view_account_info = $get_module['view_account_info'];
$del_withdrawal_request = $get_module['del_withdrawal_request'];
$approve_withdrawal_request = $get_module['approve_withdrawal_request'];
$view_expense = $get_module['view_expense'];
$del_expense = $get_module['del_expense'];
$edit_expense = $get_module['edit_expense '];
$view_payroll = $get_module['view_payroll'];
$add_payroll = $get_module['add_payroll'];
$del_payroll = $get_module['del_payroll'];
$edit_payroll = $get_module['edit_payroll'];
$del_cpay_history = $get_module['del_cpay_history'];
$print_cpayment = $get_module['print_cpayment'];
$export_excel_cpay_history = $get_module['export_excel_cpay_history'];
$del_branch = $get_module['del_branch'];
$edit_branch = $get_module['edit_branch'];
$approve_campaign = $get_module['approve_campaign'];
$edit_campaign = $get_module['edit_campaign'];
$del_campaign = $get_module['del_campaign'];
$new_emp = $get_module['new_emp'];
$list_emp = $get_module['list_emp'];
$edit_emp = $get_module['edit_emp'];
$send_empsms = $get_module['send_empsms'];
$del_emp = $get_module['del_emp'];
$print_emp = $get_module['print_emp'];
$export_excel_emp = $get_module['export_excel_emp']; 
$new_loan = $get_module['new_loan']; 
$del_loan = $get_module['del_loan']; 
$due_loan = $get_module['due_loan'];
$approve_loan = $get_module['approve_loan']; 
$loan_details = $get_module['loan_details'];
$print_loan = $get_module['print_loan'];
$export_excel_loanlist = $get_module['export_excel_loanlist']; 
$new_payment = $get_module['new_payment'];
$del_payment = $get_module['del_payment'];
$print_payment = $get_module['print_payment'];
$export_excel_lpayment = $get_module['export_excel_lpayment'];
$add_wallet = $get_module['add_wallet'];
$transfer_money = $get_module['transfer_money'];
$reverse_money = $get_module['reverse_money'];
$reverse_transfer = $get_module['reverse_transfer'];
$verify_account = $get_module['verify_account'];
$make_deposit = $get_module['deposit_money'];
$withdraw_deposit = $get_module['withdraw_money'];
$print_transaction = $get_module['print_transaction'];
$export_excel_transaction = $get_module['export_excel_transaction'];

?>