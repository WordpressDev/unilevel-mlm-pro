<?php 
require_once('../../../wp-config.php');
$g_criteria = ""; 
$g_criteria1 = ""; 
$g_criteria2 = ""; 
$g_criteria3 = "";
 
if(isset($_REQUEST['do'])) {
	$g_criteria1 = trim($_REQUEST['do']);
}

if(isset($_REQUEST['event'])) {
	$g_criteria2 = trim($_REQUEST['event']);
}


switch($g_criteria1)
{
	
	case "statuschange": 
		updatePaymentStatus();
		//insert_refferal_commision();		
	break;
	
}


function updatePaymentStatus() 
{ 
	global $wpdb,$table_prefix;
	if(isset($_REQUEST['userId']) && isset($_REQUEST['status']))
	{
		
		if($_REQUEST['status']=='0')
		{
		 $sql = "UPDATE {$table_prefix}mlm_users  SET `payment_status` = '0' WHERE user_id = '".$_REQUEST['userId']."'";
		$wpdb->query($sql);
		
		$uID = $wpdb->get_var("SELECT id FROM {$table_prefix}mlm_users WHERE user_id='".$_REQUEST['userId']."'");
		$sql = "UPDATE {$table_prefix}mlm_payment_status  SET `payment_status` = '0' WHERE user_id = '".$uID."'";
		$wpdb->query($sql);
		
		}
        else
		{						 
          UserStatusUpdate($_REQUEST['userId']);
	        
			
		}

		
	}  //end of else condition
	
}

function insert_refferal_commision()
{ 
	global $wpdb;
	$date = date("Y-m-d H:i:s");
	$child_ids='';
	$mlm_payout = get_option('wp_mlm_payout_settings');
	$refferal_amount=$mlm_payout['referral_commission_amount'];
	$table_prefix = mlm_core_get_table_prefix();
	

		if(isset($_REQUEST['userId']) && $_REQUEST['status'] ==1)
		{
			$table_prefix = mlm_core_get_table_prefix();
			$user_id=$_REQUEST['userId'];
			$row=$wpdb->get_row("SELECT * FROM {$table_prefix}mlm_users WHERE user_id=$user_id");
			$sponsor_key=$row->sponsor_key;
			$child_id = $row->id;
			if($sponsor_key !=0) {
			$sponsor= $wpdb->get_row("SELECT id FROM {$table_prefix}mlm_users WHERE user_key='".$sponsor_key."'"); 
			$sponsor_id=$sponsor->id;
			$sql = "INSERT INTO {$table_prefix}mlm_referral_commission SET date_notified ='$date',sponsor_id='$sponsor_id',child_id='$child_id',amount='$refferal_amount',payout_id='0'";
			$rs = $wpdb->query($sql);
			if(!$rs){_e("<span class='error' style='color:red'>Inserting  Fail</span>");}
			 
		}																					
	}
}


?>