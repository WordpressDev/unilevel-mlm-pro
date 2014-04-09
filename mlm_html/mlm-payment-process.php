<?php
//Function to compare Hash returned from the gateway and that generated in the script above.
function checkhash($HashDigest) {
   $ReturnedHash = $_POST["HashDigest"];
   if ($HashDigest == $ReturnedHash) { 
       echo "PASSED"; 
   } else { 
       echo "FAILED"; 
   } 
}
function mlm_payment_status_details_page() {

	global $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	$error = '';
	$chk = 'error';
	


$mlm_method = get_option('wp_mlm_payment_method');

if($mlm_method['mlm_payment_method']=='paypal')
{

 if($_POST["payment_status"]==1)
 {
 $paymentStatus=1;
 }
 else {
 $paymentStatus=0;
 }

$OrderID=$_POST["item_number"];

}
else if($mlm_method['mlm_payment_method']=='cardsave') 
{
//echo "<pre>"; print_r($_POST); exit;
$mlm_pay_settings = get_option('wp_mlm_payment_settings');

$PreSharedKey=$mlm_pay_settings["mlm-pre-shared-key"];
$MerchantID=$mlm_pay_settings["mlm-merchant-id"];
$Password=$mlm_pay_settings["mlm-merchant-password"];
$OrderID=$_POST["OrderID"];
//magic quotes fix
if (get_magic_quotes_gpc()) {
	$_POST = array_map('stripslashes', $_POST);
}

//Generate Hashstring - use combination of post variables and variables from config.php
$HashString="PreSharedKey=" . $PreSharedKey;
$HashString=$HashString . '&MerchantID=' . $_POST["MerchantID"];
$HashString=$HashString . '&Password=' . $Password;
$HashString=$HashString . '&StatusCode=' . $_POST["StatusCode"];
$HashString=$HashString . '&Message=' . $_POST["Message"];
$HashString=$HashString . '&PreviousStatusCode=' . $_POST["PreviousStatusCode"];
$HashString=$HashString . '&PreviousMessage=' . $_POST["PreviousMessage"];
$HashString=$HashString . '&CrossReference=' . $_POST["CrossReference"];
$HashString=$HashString . '&AddressNumericCheckResult=' . $_POST["AddressNumericCheckResult"];
$HashString=$HashString . '&PostCodeCheckResult=' . $_POST["PostCodeCheckResult"];
$HashString=$HashString . '&CV2CheckResult=' . $_POST["CV2CheckResult"];
$HashString=$HashString . '&ThreeDSecureAuthenticationCheckResult=' . $_POST["ThreeDSecureAuthenticationCheckResult"];
$HashString=$HashString . '&CardType=' . $_POST["CardType"];
$HashString=$HashString . '&CardClass=' . $_POST["CardClass"];
$HashString=$HashString . '&CardIssuer=' . $_POST["CardIssuer"];
$HashString=$HashString . '&CardIssuerCountryCode=' . $_POST["CardIssuerCountryCode"];
$HashString=$HashString . '&Amount=' . $_POST["Amount"];
$HashString=$HashString . '&CurrencyCode=' . $_POST["CurrencyCode"];
$HashString=$HashString . '&OrderID=' . $OrderID;
$HashString=$HashString . '&TransactionType=' . $_POST["TransactionType"];
$HashString=$HashString . '&TransactionDateTime=' . $_POST["TransactionDateTime"];
$HashString=$HashString . '&OrderDescription=' . $_POST["OrderDescription"];
$HashString=$HashString . '&CustomerName=' . $_POST["CustomerName"];
$HashString=$HashString . '&Address1=' . $_POST["Address1"];
$HashString=$HashString . '&Address2=' . $_POST["Address2"];
$HashString=$HashString . '&Address3=' . $_POST["Address3"];
$HashString=$HashString . '&Address4=' . $_POST["Address4"];
$HashString=$HashString . '&City=' . $_POST["City"];
$HashString=$HashString . '&State=' . $_POST["State"];
$HashString=$HashString . '&PostCode=' . $_POST["PostCode"];
$HashString=$HashString . '&CountryCode=' . $_POST["CountryCode"];
$HashString=$HashString . '&EmailAddress=' . $_POST["EmailAddress"];
$HashString=$HashString . '&PhoneNumber=' . $_POST["PhoneNumber"];

//Encode HashDigest using SHA1 encryption (and create HashDigest for later use) - This is used as a checksum to ensure that the form post from the gateway back to this page hasn't been tampered with.
 $HashDigest = sha1($HashString);  

 if($_POST["StatusCode"]==0)
 {
 $paymentStatus=1;
 }
 else {
 $paymentStatus=0;
 }

 
 }


//print $HashString;
 
 if($paymentStatus==1)
 {
 
    $user_id = $wpdb->get_var("SELECT user_id FROM {$table_prefix}mlm_payment_status  WHERE `order_id`='".$OrderID."'");
    $user_child=$wpdb->get_var("SELECT user_id FROM {$table_prefix}mlm_users  WHERE `id`='".$user_id."'");	
        //get no. of level
	UserStatusUpdate($user_child);
	
	//}  //end of for loop
 
 $msg = "<span style='color:green;'>Thank you. Your payment has been processed successfully.<a href=''>Click here</a> to go your Dashboard.</span>";
 _e($msg);
 }
else {

 $msg = "<span style='color:red;'>Sorry. There was an error processing your payment. <a href=''>Click here</a> to go your Dashboard.</span>";
 _e($msg);


}

} ?>