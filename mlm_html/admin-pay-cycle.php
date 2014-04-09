<?php 
function wpmlm_run_pay_cycle()
{
	
	$returnVar  = wpmlm_run_PayCycleFunctions();	
	return $returnVar; 
	
}

function wpmlm_run_PayCycleFunctions()
{
	

	$payoutMasterId = createPayoutMaster(); 
	
	global $table_prefix, $wpdb; 
	
	
	$query = $wpdb->get_results("
																													SELECT user_key FROM {$table_prefix}mlm_users 
																													WHERE (payment_status= '1' OR payment_status= '2')
																													AND banned = '0'
																												ORDER BY id
																									");
	$num = $wpdb->num_rows;
	
	if($num)
	{
	foreach($query as $row)
		{
	$sql=  "SELECT 
				SUM(amount) AS commission  
			FROM 
				{$table_prefix}mlm_commission 
			WHERE 
			parent_id='".$row->user_key."'
			AND
				payout_id = 0
			GROUP BY 
				parent_id	
			";
	
	$rs = $wpdb->get_var($sql);	
			
			if($rs)
			{ 
			$commissionAmt = $rs;
			
			}
			else { 
			$commissionAmt=0;
			}
	
	   if(mlmIsEligibleForBonus($row->user_key)){ 
			$bonusAmt= DisplayCalculateBonus($row->user_key,'distribute'); 
              
			 }
			else {
			 $bonusAmt=0;
			 }
	   
	   
	 
	   
	   $u_id = $wpdb->get_var("
							SELECT id 
							FROM {$table_prefix}mlm_users 
							WHERE `user_key` = '".$row->user_key."'
				");
			
			$userId = $u_id; 
			
						
			$capLimitAmt = 0; 
			
			
			
			$totalAmt = $commissionAmt + $bonusAmt;
			
			if(round($totalAmt)>0) {
			$total = round($totalAmt); 
			
	       /***********************************************************
			INSERT INTO PAYOUT TABLE
			***********************************************************/ 
			$sql_payout = "INSERT INTO 
							{$table_prefix}mlm_payout
							(
								user_id, date, payout_id, commission_amount, 
								bonus_amount,total_amt
							) 
							VALUES 					
							(
								'".$userId."', '".date('Y-m-d H:i:s')."', '".$payoutMasterId."', '".$commissionAmt."', 
								'".$bonusAmt."','".$total."'
							)";  
							
							
		   					
			//mysql_query("UPDATE {$table_prefix}mlm_referral_commission set payout_id='$payoutMasterId' where sponsor_id='$userId' AND payout_id=0");		
			$closing_bal = $wpdb->get_var("select closing_bal from {$table_prefix}mlm_transaction  where id= (select max(id) from {$table_prefix}mlm_transaction where user_id ='".$userId."')");	
			$opening_bal = $closing_bal;
                        $closing_bal = $opening_bal+$totalAmt;
			$sql_transaction = "INSERT INTO {$table_prefix}mlm_transaction set	
								user_id ='".$userId."', 
								cr_id = '".$payoutMasterId."',
                                                                opening_bal = '".$opening_bal."',
								cr_amount = '".$totalAmt."', 
                                                                closing_bal = '".$closing_bal."',    
								transaction_date='".date('Y-m-d H:i:s')."',
								transaction_type='1',
                                                                comment = 'Amount Credited by payout ID {$payoutMasterId}'";
			mysql_query($sql_transaction);
                                                        
                        $rs_payout = mysql_query($sql_payout);
			$insert_id = mysql_insert_id();
			
			/***********************************************************
			Update Commission table Payout Id
			***********************************************************/ 
			if(isset($insert_id) && $insert_id >0)
			{
				$sql_comm = "UPDATE {$table_prefix}mlm_commission 
								SET 
									payout_id= '".$payoutMasterId."'
								WHERE 
									parent_id = '".$row->user_key."' AND 
									payout_id = '0'
								";
				$rs_comm = mysql_query($sql_comm); 					
			
			}
			/***********************************************************
			Update Bonus table Payout Id
			***********************************************************/ 
			if(isset($insert_id) && ($insert_id >0) && ($bonusAmt >0))
			{
				$sql_bon = "UPDATE {$table_prefix}mlm_bonus 
								SET 
									payout_id= '".$payoutMasterId."'
								WHERE 
									mlm_user_id = '".$userId."' AND 
									payout_id = '0'
								";
				$rs_bon = mysql_query($sql_bon); 					
			
			}
	
	 
	   }
	
	
	
	}
	}
	
	return "Payout Run Successfully";
	
	
	
	
	
	$sql=  "SELECT 
				id, date_notified, parent_id, child_ids, amount, SUM(amount) AS commission  
			FROM 
				{$table_prefix}mlm_commission 
			WHERE 
				payout_id = 0
			GROUP BY 
				parent_id	
			";
	
	//_e($sql); exit; 
	
		
	$rs = $wpdb->get_results($sql); 
	if($wpdb->num_rows > 0)
	{
		foreach($rs as $row)
		{
			$u_id = $wpdb->get_var("
							SELECT id 
							FROM {$table_prefix}mlm_users 
							WHERE `user_key` = '".$row->parent_id."'
				");
			
			$userId = $u_id; 
			$commissionAmt = $row->commission;
			$bonusAmt = getBonusAmountById($userId);
		     if(isset($bonusAmt))
                     {  $bonusAmt=$bonusAmt;  }
                     else {   $bonusAmt=0; }
			
			$capLimitAmt = 0; 
			$totalAmt = $commissionAmt + $bonusAmt;
			
			
			$total = round($totalAmt); 
			
			
				
					
			/***********************************************************
			INSERT INTO PAYOUT TABLE
			***********************************************************/ 
			$sql_payout = "INSERT INTO 
							{$table_prefix}mlm_payout
							(
								user_id, date, payout_id, commission_amount, 
								bonus_amount,total_amt
							) 
							VALUES 					
							(
								'".$userId."', '".date('Y-m-d H:i:s')."', '".$payoutMasterId."', '".$commissionAmt."', 
								'".$bonusAmt."','".$total."'
							)";  
							
							
		   					
			//mysql_query("UPDATE {$table_prefix}mlm_referral_commission set payout_id='$payoutMasterId' where sponsor_id='$userId' AND payout_id=0");		
			$closing_bal = $wpdb->get_var("select closing_bal from {$table_prefix}mlm_transaction  where id= (select max(id) from {$table_prefix}mlm_transaction where user_id ='".$userId."')");	
			$opening_bal = $closing_bal;
                        $closing_bal = $opening_bal+$totalAmt;
			$sql_transaction = "INSERT INTO {$table_prefix}mlm_transaction set	
								user_id ='".$userId."', 
								cr_id = '".$payoutMasterId."',
                                                                opening_bal = '".$opening_bal."',
								cr_amount = '".$totalAmt."', 
                                                                closing_bal = '".$closing_bal."',    
								transaction_date='".date('Y-m-d H:i:s')."',
								transaction_type='1',
                                                                comment = 'Amount Credited by payout ID {$payoutMasterId}'";
			mysql_query($sql_transaction);
                                                        
                        $rs_payout = mysql_query($sql_payout);
			$insert_id = mysql_insert_id();
			
			/***********************************************************
			Update Commission table Payout Id
			***********************************************************/ 
			if(isset($insert_id) && $insert_id >0)
			{
				$sql_comm = "UPDATE {$table_prefix}mlm_commission 
								SET 
									payout_id= '".$payoutMasterId."'
								WHERE 
									parent_id = '".$row->parent_id."' AND 
									payout_id = '0'
								";
				$rs_comm = mysql_query($sql_comm); 					
			
			}
			/***********************************************************
			Update Bonus table Payout Id
			***********************************************************/ 
			if(isset($insert_id) && ($insert_id >0) && ($bonusAmt >0))
			{
				$sql_bon = "UPDATE {$table_prefix}mlm_bonus 
								SET 
									payout_id= '".$payoutMasterId."'
								WHERE 
									mlm_user_id = '".$userId."' AND 
									payout_id = '0'
								";
				$rs_bon = mysql_query($sql_bon); 					
			
			}
						
		}	
	
        }
	
	return "Payout Run Successfully";
	
}

function createPayoutMaster()
{
	global $table_prefix; 
	
	//$mlm_payout = get_option('wp_mlm_payout_settings');
	//$capLimitAmt = $mlm_payout['cap_limit_amount'];
	$capLimitAmt = 0;
	$sql = "INSERT INTO {$table_prefix}mlm_payout_master(date,cap_limit) VALUES ('".date('Y-m-d H:i:s')."','$capLimitAmt')"; 
	$res = mysql_query($sql);
	$pay_master_id = mysql_insert_id();
	
	return $pay_master_id; 
}

function getBonusAmountById($userId)
{
//$userId is Mlm_user Table Id value
	global $table_prefix; 
         $sql = "SELECT 
				amount, SUM(amount) AS bonus, payout_id 
			FROM 
				{$table_prefix}mlm_bonus 
			WHERE 
				mlm_user_id ='".$userId."' and payout_id= 0
				
			GROUP BY 
				mlm_user_id 			
		";
	
	$rs = mysql_query($sql);
	
	if(mysql_num_rows($rs)>0)
	{
		$row = mysql_fetch_array($rs); 
		
		$bonus = $row['bonus']; 
		
	}	
	
	if(!empty($bonus)) return  $bonus;
 
}


?>