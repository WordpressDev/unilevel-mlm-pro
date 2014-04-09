<?php


/**** this file is used to create costom function ***********/

include('custom-functions.php');
/**** this file is used to create costom function ***********/
//Take user's key and return user's ID


//generate random key
function generateKey()
{
    /// Random characters
	$characters = array("0","1","2","3","4","5","6","7","8","9");

	// set the array
	$keys = array();

	// set length
	$length = 9;

	// loop to generate random keys and assign to an array
	while(count($keys) < $length) 
	{
		$x = mt_rand(0, count($characters)-1);
		if(!in_array($x, $keys)) 
       		$keys[] = $x;
	}

	// extract each key from array
	$random_chars='';
	foreach($keys as $key)
   		$random_chars .= $characters[$key];

	// display random key
	return $random_chars;
}



//return the logeed user's fa_user key
function get_user_key_admin($username)
{
	global $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	$user_key = $wpdb->get_var("
																												SELECT user_key 
																												FROM {$table_prefix}mlm_users 
																												WHERE username = '".$username."'
																								");
																							
	return $user_key;
}

//return the logeed user's fa_user id
function getUserIdByUsername()
{
	$table_prefix = mlm_core_get_table_prefix();
	
	global $current_user, $wpdb;
	
	get_currentuserinfo();
	$username = $current_user->user_login;

	$id = $wpdb->get_var("
																						SELECT id 
																						FROM {$table_prefix}mlm_users 
																						WHERE username = '".$username."'
																				");
	return $id;
}

function checkKey($key)
{
	$table_prefix = mlm_core_get_table_prefix();
	global $wpdb;
	$user_key = $wpdb->get_var("
																											SELECT user_key 
						 																					FROM {$table_prefix}mlm_users 
						  																				WHERE `user_key` = '".$key."' 
						  																				AND banned = '0'
																									");
	if(!$user_key)
			return false;
	
	return true;
}

function checkallowed($key)
{
	global $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	
	$username = $wpdb->get_var("
																													SELECT username 
						  																						FROM {$table_prefix}mlm_users 
						 																							WHERE  parent_key = '".$key."'
																									");
	return $wpdb->num_rows;
}

function totalMyPersonalSales($sponsor)
{
	global $wpdb;		
	$table_prefix = mlm_core_get_table_prefix();

	$num = $wpdb->get_var("
																								SELECT COUNT(*) AS num
																								FROM {$table_prefix}mlm_users
																								WHERE sponsor_key = '".$sponsor."'
																				");
	return $num;
}

function activeUsersOnPersonalSales($sponsor)
{
	global $wpdb;		
	$table_prefix = mlm_core_get_table_prefix();
	$num = $wpdb->get_var("
																							SELECT COUNT(*) AS num
																							FROM {$table_prefix}mlm_users
																							WHERE sponsor_key = '".$sponsor."'
																							AND payment_status = '1'
																				");
	return $num;
}

function activeNotActive($status)
{
	if($status == '1')
		return 'Active';
	else
		return 'Not Active';
}


function myFivePersonalUsers($pkey)
{
	global $wpdb;	
	$table_prefix = mlm_core_get_table_prefix();
	$sql = "SELECT username, payment_status
								FROM {$table_prefix}mlm_users
								WHERE sponsor_key = '".$pkey."'
								ORDER BY id DESC
								LIMIT 0,5";
	$results = $wpdb->get_results($sql);
 $i = 0;
	if($wpdb->num_rows > 0)
	{
		foreach($results as $data)
		{
			$users[$i]['username'] = $data->username;
			$users[$i]['payment_status'] = activeNotActive($data->payment_status);
			$i++;
		}
	}
	else
	{
		$users[$i]['username'] = 'No Member Found';
		$users[$i]['payment_status'] = '';
	}
	return $users;
}

function getSponsorName($key)
{
	global $wpdb;	
	$table_prefix = mlm_core_get_table_prefix();
	$sql = "SELECT username
								FROM {$table_prefix}mlm_users
								WHERE user_key = '".$key."'";
	$username = $wpdb->get_var($sql);
	
	return $username;
}


function myTotalPersonalUsers($pkey)
{
	global $wpdb;	
	$table_prefix = mlm_core_get_table_prefix();
	$sql = "SELECT username, payment_status, user_key
								FROM {$table_prefix}mlm_users
								WHERE sponsor_key = '".$pkey."'
								ORDER BY id DESC";
	$results = $wpdb->get_results($sql);
 $i = 0;
	if($wpdb->num_rows > 0)
	{
		foreach($results as $data)
		{
			$users[$i]['username'] = $data->username;
			$users[$i]['user_key'] = $data->user_key;
			$users[$i]['payment_status'] = activeNotActive($data->payment_status);
			$i++;
		}
	}
	else
	{
		$users[$i]['username'] = 'No Member Found';
		$users[$i]['payment_status'] = '';
	}
	return $users;
}



function totalSales($pkey)
{
	global $wpdb;	
	$table_prefix = mlm_core_get_table_prefix();
	$sql = "SELECT username, payment_status, user_key, sponsor_key, leg
								FROM {$table_prefix}mlm_users
								WHERE user_key IN
								(
												SELECT ukey 
												FROM {$table_prefix}mlm_rightleg
												WHERE pkey = '".$pkey."'
												ORDER BY id DESC
								)
								ORDER BY id DESC";
	$results = $wpdb->get_results($sql);
 $i = 0;
	if($wpdb->num_rows > 0)
	{
		foreach($results as $data)
		{
			$rightUsers[$i]['username'] = $data->username;
			$rightUsers[$i]['user_key'] = $data->user_key;
			$rightUsers[$i]['sponsor_key'] = getSponsorName($data->sponsor_key);
			$rightUsers[$i]['leg'] = legPlacement('1');
			$rightUsers[$i]['payment_status'] = activeNotActive($data->payment_status);
			$i++;
		}
	}
	/*else
	{
		$rightUsers[$i]['username'] = 'No Member Found';
		$rightUsers[$i]['payment_status'] = '';
	}*/
	
	$sql = "SELECT username, payment_status, user_key, sponsor_key, leg
								FROM {$table_prefix}mlm_users
								WHERE user_key IN
								(
												SELECT ukey 
												FROM {$table_prefix}mlm_leftleg
												WHERE pkey = '".$pkey."'
												ORDER BY id DESC
								)
								ORDER BY id DESC";
								
	$results = $wpdb->get_results($sql);
 $i = 0;
	if($wpdb->num_rows > 0)
	{
		foreach($results as $data)
		{
			$leftUsers[$i]['username'] = $data->username;
			$leftUsers[$i]['user_key'] = $data->user_key;
			$leftUsers[$i]['sponsor_key'] = getSponsorName($data->sponsor_key);
			$leftUsers[$i]['leg'] = legPlacement('0');
			$leftUsers[$i]['payment_status'] = activeNotActive($data->payment_status);
			$i++;
		}
	}
	/*else
	{
		$leftUsers[$i]['username'] = 'No Member Found';
		$leftUsers[$i]['payment_status'] = '';
	}*/
	
		 if((!empty($leftUsers) && count($leftUsers)!=0) || (!empty($rightUsers) && count($rightUsers)!=0))
 	 { 
 	 				$consultant = array($leftUsers, $rightUsers);
 	 				return $consultant;
 	  
 	 } 
 	 else
 	 {
 	 				$default[0]['username'] ='No Members Found';
 	 				$default[0]['payment_status']= ''; 
 	 				//echo "<pre>";print_r($default); exit; 
 	    $consultant = array($default);
 	    return $consultant;
 	 }
}

function show_message_after_plugin_activation() 
{
	global $wpdb;	
	$table_prefix = mlm_core_get_table_prefix();
	
	$check1 = $wpdb->get_var("
												SELECT COUNT(*) AS num 
												FROM {$table_prefix}mlm_users
											");
	
	$check2 = $wpdb->get_var("
												SELECT COUNT(*) AS num 
												FROM {$table_prefix}options
												WHERE option_name = 'wp_mlm_general_settings'
											");
											
	$check3 = $wpdb->get_var("
												SELECT COUNT(*) AS num 
												FROM {$table_prefix}options
												WHERE option_name = 'wp_mlm_eligibility_settings'
											");
											
	$check4 = $wpdb->get_var("
												SELECT COUNT(*) AS num 
												FROM {$table_prefix}options
												WHERE option_name = 'wp_mlm_payout_settings'
											");
											
	$check5 = $wpdb->get_var("
												SELECT COUNT(*) AS num 
												FROM {$table_prefix}options
												WHERE option_name = 'wp_mlm_regular_bonus_settings'
											");
	$check6 = $wpdb->get_var("
												SELECT COUNT(*) AS num 
												FROM {$table_prefix}options
												WHERE option_name = 'wp_mlm_royalty_bonus_settings'
											");
											
	$check7 = $wpdb->get_var("
												SELECT COUNT(*) AS num 
												FROM {$table_prefix}options
												WHERE option_name = 'wp_mlm_withdrawal_method_settings'
											");
												
	//wp_mlm_general_settings
	//wp_mlm_eligibility_settings
	//wp_mlm_payout_settings
	//wp_mlm_bonus_settings

	$flag = 0;
	if($check1 == 0)
	{
		$msg = _e("<div class='updated fade'><p><strong>
Please create the first user account in the MLM Network and
complete the other MLM settings. </strong>",'unilevel-mlm-pro');
		$tab = 'createuser';
		$flag = 1;
	}
	else if($check2 == 0)
	{
		$msg = _e("<div class='updated fade'><p><strong>It seems you haven't finished configuring the settings of the MLM Plugin. </strong>",'unilevel-mlm-pro');
		$tab ='general';
		$flag = 1;
	}
	else if($check3 == 0)
	{
		$msg = _e("<div class='updated fade'><p><strong>It seems you haven't finished configuring the settings of the MLM Plugin. </strong>",'unilevel-mlm-pro');
		$tab = 'eligibility';
		$flag = 1;
	}
	else if($check4 == 0)
	{
		$msg = _e("<div class='updated fade'><p><strong>It seems you haven't finished configuring the settings of the MLM Plugin. </strong>",'unilevel-mlm-pro');
		$tab = 'payout';
		$flag = 1;
	}
	else if($check5 == 0)
	{
		$msg = _e("<div class='updated fade'><p><strong>It seems you haven't finished configuring the settings of the MLM Plugin. </strong>",'unilevel-mlm-pro');
		$tab = 'regular_bonus';
		$flag = 1;
	}
	else if($check6 == 0)
	{
		$msg = _e("<div class='updated fade'><p><strong>It seems you haven't finished configuring the settings of the MLM Plugin. </strong>",'unilevel-mlm-pro');
		$tab = 'royalty_bonus';
		$flag = 1;
	}
	else if($check7 == 0)
	{
		$msg = _e("<div class='updated fade'><p><strong>It seems you haven't finished configuring the settings of the MLM Plugin. </strong>",'unilevel-mlm-pro');
		$tab = 'deduction';
		$flag = 1;
	}
	
	if($flag == 1)
	{
      if($check1 == 0) {
		echo "<a href='".get_bloginfo('url')."/wp-admin/admin.php?page=admin-settings&tab=".$tab."'>".__('click here','unilevel-mlm-pro')."</a>.</p></div>";
		}
		else {
	echo "<a href='".get_bloginfo('url')."/wp-admin/admin.php?page=admin-settings&tab=".$tab."'>".__('click here','unilevel-mlm-pro')."</a><strong> to go to the settings page and complete the remaining settings.</strong></p></div>";	
		
		
		
		}
		
		
		
		}
}

remove_filter('the_content', 'wpautop');

/************** Here begin the code for calculate and distribute the commission***********************/

function mlmGetUserNameByKey($key)
{
	global $wpdb;	
	// get table's prefix used by your site database schema
	$table_prefix = mlm_core_get_table_prefix();
	
	$username = $wpdb->get_var("
																												SELECT username 
			  									 															FROM {$table_prefix}mlm_users 
			   									 														WHERE user_key = '".$key."'
																								");
																								
		return $username;
}
function wpmlm_run_pay_display()
{
global $wpdb;	
	$table_prefix = mlm_core_get_table_prefix();
	
	//select all active users and give commision to their parents
	$query = $wpdb->get_results("
																													SELECT user_key FROM {$table_prefix}mlm_users 
																													WHERE (payment_status= '1' OR payment_status= '2')
																													AND banned = '0'
																												ORDER BY id
																									");
	$num = $wpdb->num_rows;
	
	if($num)
	{

	
	$i=0;
	foreach($query as $result)
		{ 

		$totalAmt=0;
		$commissionAmt=0;
		$bonusAmt=0;
	
	if(mlmIsEligibleForBonus($result->user_key)){ 
			$bonusAmt= DisplayCalculateBonus($result->user_key); 
              
			 }
			else {
			 $bonusAmt=0;
			 }
			 
			 

   $sql=  "SELECT 
				SUM(amount) AS commission  
			FROM 
				{$table_prefix}mlm_commission 
			WHERE 
			parent_id='".$result->user_key."'
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
			
         	if($commissionAmt>0 || $bonusAmt>0) {
			
			$u_id = $wpdb->get_row("
							SELECT id,user_id 
							FROM {$table_prefix}mlm_users 
							WHERE `user_key` = '".$result->user_key."'
				");
			$userId = $u_id->id;
			
			
			$totalAmt = $commissionAmt +  $bonusAmt;	
			
			$user_info = get_userdata($u_id->user_id);
			 
			    $displayDataArray[$i]	['username'] = $user_info->user_login;
				$displayDataArray[$i]	['first_name'] = $user_info->first_name;
				$displayDataArray[$i]	['last_name'] = $user_info->last_name;
				$displayDataArray[$i]	['commission'] = $commissionAmt;
				$displayDataArray[$i]	['bonus'] = $bonusAmt;
				$displayDataArray[$i]	['net_amount'] = $totalAmt;
				$i++;
			 
			  }
			 
			 
		}
         
		 if(!isset($displayDataArray)) { $displayDataArray = "None";}
		 
	  
	}
	else 
	{   
		$displayDataArray = "None";
		
	}
		
return $displayDataArray;
	//return "Bonus";

}



function DisplayCalculateBonus($key,$ID="")
{
	
	global $wpdb;	
	$table_prefix = mlm_core_get_table_prefix();
	$Bonus=0;
	//get the eligibility for Regular bonus
	$mlm_regular_bonus = get_option('wp_mlm_regular_bonus_settings');
	$no_of_ref='';
	$bonus_distribute=0;



	if(!empty($mlm_regular_bonus)) 
	{
	
	  $array=$mlm_regular_bonus['per_ref'];
       foreach ($array as $k=>$v):
       $sum = $sum+$v;
       endforeach;
       $total_refferes=$sum;
		 //count total direct referrals
		$total_users = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_users  WHERE sponsor_key = '".$key."' AND payment_status = '1' AND banned = '0'");
	
	
	   $paidBonus = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_hierarchy WHERE bonus_status='1' AND pid='".$key."' AND level = '1'");
      
	  if($total_refferes >= $paidBonus)
	  {
	  
	  
	  
	$Bonus=0;
$leftUser=0;
$total_users = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_users  WHERE sponsor_key = '".$key."' AND payment_status = '1' AND banned = '0'");

$paidBonus = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_hierarchy WHERE bonus_status='1' AND pid='".$key."' AND level = '1'");


$totalPaid = $wpdb->get_var("SELECT COUNT( h.cid) AS num FROM {$table_prefix}mlm_users as u INNER JOIN {$table_prefix}mlm_hierarchy as h ON h.cid=u.user_key WHERE h.pid = '".$key."' AND h.level = '1' AND h.slab = '0' AND u.payment_status = '1' AND u.banned ='0'");

$count_slab=$wpdb->get_var("SELECT MAX(slab) AS slab FROM {$table_prefix}mlm_hierarchy WHERE pid='".$key."'"); 
if($count_slab>0)
{
$slab_level=$count_slab;
}
else
{
$slab_level=0;
}

for($i=(empty($slab_level)?0:$slab_level); $i<count($mlm_regular_bonus['per_ref']);$i++)
{
 $totaleligible=$totalPaid-$leftUser;
$mlm_refferal = $mlm_regular_bonus['per_ref'][$i];

if($totaleligible>=$mlm_refferal)
{

$amount= $mlm_regular_bonus['pay_value'][$i];
$Bonus=	$Bonus + $amount; 

$slab_level=$i+1;

if($ID=='distribute')
{  
$mlm_user_id = getuseridbykey($key);
insertBonusSlab($mlm_user_id, $amount,1,1);
		
$countids=$wpdb->get_var("SELECT SUBSTRING_INDEX( GROUP_CONCAT( cid ORDER BY id ASC SEPARATOR  ',' ) ,  ',', '".$mlm_refferal."' ) AS cids FROM  {$table_prefix}mlm_hierarchy WHERE bonus_status='0' AND pid = '".$key."' AND level = '1'"); 
		
$update = $wpdb->query("UPDATE {$table_prefix}mlm_hierarchy SET bonus_status = '1',slab='".$slab_level."' WHERE cid IN(".$countids.") AND  pid = '".$key."' AND level = '1'");

$leftUser=$leftUser+$mlm_refferal;		
}
else 
{
$leftUser=$leftUser+$mlm_refferal;
}


  }

} //end of for loop  
	
   }  // end of if condition for already distribute the regular bonus to all	
	
}
	// Condition for Royalty Bonus
	$mlm_royalty_bonus = get_option('wp_mlm_royalty_bonus_settings');
    
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
  if(!empty($mlm_royalty_bonus)) 
  {
  

 $fixarray=$mlm_royalty_bonus['per_ref'];

 $total_users = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_users  WHERE sponsor_key = '".$key."' AND payment_status = '1' AND banned = '0'");
 $i=0;
foreach ($fixarray as $yournumber)
{

if(!isset($Bonus)) { $Bonus=0;}

       if($yournumber <= $total_users) 
	   {
         $refferal[]= $mlm_royalty_bonus['per_ref'][$i];
		 $levels[]=$mlm_royalty_bonus['level'][$i];
		 $percent[]=$mlm_royalty_bonus['pay_value'][$i];
       }
	   $i++;
}


if(isset($levels)) {

  for($i=1;$i<=$mlm_no_of_level;$i++)
	{
	
	if (in_array($i,$levels)) 
	{
	
	$uniquevalue = array_diff($levels,array($i));
	
	//Get Similar value which contain the same level 
	$similar= array_diff($levels,$uniquevalue);
	
	
		foreach($similar as $ky=>$value)
	{ 
	//Assign the refferal as a KEY and Percentage as a Value of $new_var
	$new_var[$refferal[$ky]]=$percent[$ky];
	
	
	}
	
	//get max value of Index
	
	$max_key = max(array_keys($new_var));
	
	 $amount=$new_var[$max_key];
	
	
			$qry = $wpdb->get_row("SELECT GROUP_CONCAT( h.cid) AS id 
                                                FROM {$table_prefix}mlm_users as u INNER JOIN {$table_prefix}mlm_hierarchy as h ON h.cid=u.user_key
                                                WHERE h.pid = '".$key."'
                                                AND h.level = '".$i."'
                                                AND u.payment_status = '1'
                                                AND u.banned = '0'
					");
								
			
	
	//		if($wpdb->num_rows > 0) {

	if($qry->id) {

		$sum = $wpdb->get_var("SELECT SUM(amount) as total FROM {$table_prefix}mlm_commission WHERE parent_id IN(".$qry->id.") AND bonus_status=0");
	
	if($sum>0) 
	{
		$amt= $amount;
		$total= (($sum * $amt) / 100 );  
		$mlm_user_id = getuseridbykey($key);
		
		
		$Bonus=	$Bonus + $total;
		
		// If AALL IZ Well Button Press Run PAyout
		if($ID=='distribute')
		{  
		//For Royalty 3rd attribute will be 2
		insertBonusSlab($mlm_user_id, $total,2,$i);
		
	 $wpdb->query("UPDATE {$table_prefix}mlm_commission SET bonus_status = '1' WHERE parent_id IN(".$qry->id.") AND bonus_status=0");
	 
	  } //Run Payout
	 
	 
	 }
	 
	 
	 }
	
	}
	 
  }
   
   } // if isset Level
  
  }
  //End of Royalty Bonus 
  	return $Bonus;
}








/************************* Here begin of the code for calculate  and distribute the bonus *****************************************/
function mlmDistributeBonus()
{
	global $wpdb;	
	$table_prefix = mlm_core_get_table_prefix();
	
	//select all active users and give commision to their parents
	$query = $wpdb->get_results("
																													SELECT user_key FROM {$table_prefix}mlm_users 
																													WHERE payment_status= '1' 
																													AND banned = '0'
																												ORDER BY id
																									");
	$num = $wpdb->num_rows;
	
	if($num)
	{
		foreach($query as $result)
		{    
			if(mlmIsEligibleForBonus($result->user_key)){ 
			 mlmCalculateBonus($result->user_key); 
              
			 }
				
		}
	}
	return "Bonus";
}
function mlmIsEligibleForBonus($key)
{
	global $wpdb;	
	// get table's prefix used by your site database schema
	$table_prefix = mlm_core_get_table_prefix();
	//$top_level = get_top_level_user();
	$mlm_settings = get_option('wp_mlm_eligibility_settings');
	$eligible= $mlm_settings['personal_referrer'];
	$results = $wpdb->get_results("
																														SELECT user_key 
																														FROM {$table_prefix}mlm_users 
																														WHERE banned = '0' 
																														AND payment_status = '1' 
																														AND sponsor_key = '".$key."'
																																																		");
	$num = $wpdb->num_rows;
	
	if(($num>0) && ($num >=$eligible))
	{
		return true;
	} // end if condition
	return false;
}	
function mlmCalculateBonus($key)
{
	
	global $wpdb;	
	$table_prefix = mlm_core_get_table_prefix();
	
	//get the eligibility for Regular bonus
	$mlm_regular_bonus = get_option('wp_mlm_regular_bonus_settings');
	$no_of_ref='';
	if(!empty($mlm_regular_bonus)) 
	{
	for($i=0;$i<count($mlm_regular_bonus['per_ref']);$i++)
	{
	 $mlm_refferal = $mlm_regular_bonus['per_ref'][$i];
	 $no_of_ref=$no_of_ref+$mlm_refferal;
	 //count total direct referrals
		$total_users = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_users  WHERE sponsor_key = '".$key."' AND payment_status = '1' AND banned = '0'");
	
	
		$paid_bonus = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_hierarchy WHERE bonus_status='1' AND pid='".$key."' AND level = '1'");
		
		$slab=$total_users - $paid_bonus;
if($paid_bonus>0) 
{

if($paid_bonus == $no_of_ref)
{

continue;

}
else if($slab >= $mlm_refferal)
{


        $amount= $mlm_regular_bonus['pay_value'][$i];
		$mlm_user_id = getuseridbykey($key);
		
		insertBonusSlab($mlm_user_id, $amount,1,1);
		
		
		$countids=$wpdb->get_var("SELECT SUBSTRING_INDEX( GROUP_CONCAT( cid ORDER BY id ASC SEPARATOR  ',' ) ,  ',', '".$mlm_refferal."' ) AS cids FROM  {$table_prefix}mlm_hierarchy WHERE bonus_status='0' AND pid = '".$key."' AND level = '1'"); 
		
$update = $wpdb->query("UPDATE {$table_prefix}mlm_hierarchy SET bonus_status = '1' WHERE cid IN(".$countids.") AND  pid = '".$key."' AND level = '1'");
		
		
}

}
else 
{	
        //count total direct referrals
		$query = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_users as u INNER JOIN {$table_prefix}mlm_hierarchy as h ON h.cid=u.user_key WHERE h.pid = '".$key."' AND h.level = '1' AND u.payment_status = '1' AND h.bonus_status='0' AND u.banned = '0'");
		if($query >= $mlm_refferal)
		{
		
		$amount= $mlm_regular_bonus['pay_value'][$i];
		$mlm_user_id = getuseridbykey($key);
		
		insertBonusSlab($mlm_user_id, $amount,1,1);
		
		$count_ids=$wpdb->get_var("SELECT SUBSTRING_INDEX( GROUP_CONCAT( cid ORDER BY id ASC SEPARATOR  ',' ) ,  ',', '".$mlm_refferal."' ) AS cids FROM  {$table_prefix}mlm_hierarchy WHERE bonus_status='0' AND pid = '".$key."' AND level = '1' "); 
		
 $wpdb->query("UPDATE {$table_prefix}mlm_hierarchy SET bonus_status = '1' WHERE cid IN(".$count_ids.") AND  pid = '".$key."' AND level = '1' ");
		
		}
}
		
	}
	
}
	// Condition for Royalty Bonus
	$mlm_royalty_bonus = get_option('wp_mlm_royalty_bonus_settings');
    
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
  if(!empty($mlm_royalty_bonus)) 
  {
  

 $fixarray=$mlm_royalty_bonus['per_ref'];

 $total_users = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_users  WHERE sponsor_key = '".$key."' AND payment_status = '1' AND banned = '0'");
 $i=0;
foreach ($fixarray as $yournumber)
{
       if($yournumber <= $total_users) 
	   {
         $refferal[]= $mlm_royalty_bonus['per_ref'][$i];
		 $levels[]=$mlm_royalty_bonus['level'][$i];
		 $percent[]=$mlm_royalty_bonus['pay_value'][$i];
       }
	   $i++;
}


if(isset($levels)) {

  for($i=1;$i<=$mlm_no_of_level;$i++)
	{
	
	if (in_array($i,$levels)) 
	{
	
	$uniquevalue = array_diff($levels,array($i));
	
	//Get Similar value which contain the same level 
	$similar= array_diff($levels,$uniquevalue);
	
	
		foreach($similar as $ky=>$value)
	{ 
	//Assign the refferal as a KEY and Percentage as a Value of $new_var
	$new_var[$refferal[$ky]]=$percent[$ky];
	
	
	}
	
	//get max value of Index
	
	$max_key = max(array_keys($new_var));
	
	 $amount=$new_var[$max_key];
	
	
			$qry = $wpdb->get_row("SELECT GROUP_CONCAT( h.cid) AS id 
                                                FROM {$table_prefix}mlm_users as u INNER JOIN {$table_prefix}mlm_hierarchy as h ON h.cid=u.user_key
                                                WHERE h.pid = '".$key."'
                                                AND h.level = '".$i."'
                                                AND u.payment_status = '1'
                                                AND u.banned = '0'
					");
								
			
	
	//		if($wpdb->num_rows > 0) {

	if($qry->id) {

		$sum = $wpdb->get_var("SELECT SUM(amount) as total FROM {$table_prefix}mlm_commission WHERE parent_id IN(".$qry->id.") AND bonus_status=0");
	
	if($sum>0) 
	{
		$amt= $amount;
		$total= (($sum * $amt) / 100 );  
		$mlm_user_id = getuseridbykey($key);
		//For Royalty 3rd attribute will be 2
		insertBonusSlab($mlm_user_id, $total,2,$i);
		
		
	 $wpdb->query("UPDATE {$table_prefix}mlm_commission SET bonus_status = '1' WHERE parent_id IN(".$qry->id.") AND bonus_status=0");
	 
	 }
	 
	 
	 }
	
	}
	 
  }
   
   } // if isset Level
  
  }
  //End of Royalty Bonus 
  	
}



function distributeBonusSlab($mlm_user_id)
{
	global $wpdb;	
	$table_prefix = mlm_core_get_table_prefix();
	//count how many times bonus have been paid by the system previously
	
	$cb = $wpdb->get_var("
																							SELECT COUNT(*) AS num
																							FROM {$table_prefix}mlm_bonus
																							WHERE mlm_user_id = '".$mlm_user_id."'
																			");
	return $cb;
}

function insertBonusSlab($mlm_user_id, $amount,$bonus_type,$level)
{
	global $wpdb;		
	$table_prefix = mlm_core_get_table_prefix();
	$date = date('Y-m-d H:i:s');
	
	//deduct service charge and tds
	//$payable_amount_array = calculateTdsAndServiceCharge($amount);
	
	$insert = $wpdb->query("INSERT INTO {$table_prefix}mlm_bonus
                                (
                                                                id, date_notified, mlm_user_id, amount,bonus_type,level
                                )
                                VALUES
                                (
                                                                NULL, '".$date."', '".$mlm_user_id."', '".$amount."', '".$bonus_type."', '".$level."'
                                )
                                ");
}
/*********************** Here end the code of calculating and distributing bonus ******************************************/

function calculateTdsAndServiceCharge($amount)
{
	$mlm_payout = get_option('wp_mlm_payout_settings');
	//first calculate tds
	if($mlm_payout['tds'] != "")
	{
		$tds =  $amount * ($mlm_payout['tds'] / 100);
		//$amount = $amount - $tds;
	}
	else
		$tds = 0;
	//calculate service charge	
	if($mlm_payout['service_charge'] != "")
	{
		//$amount = $amount - $mlm_payout['service_charge'];
		$array['service_charge'] = $mlm_payout['service_charge'];
	}
	else
		$array['service_charge'] = 0.00;
		
		
	$array['amount'] = $amount;
	$array['tds'] = $tds;
	
	return $array;
}

//this function REGISTER THE SHORTCODE when plugin is activated
function register_shortcodes()
{
   //1st agru is the name of the shortcode and second is function name which is called when shortcode is triggered
   add_shortcode(MLM_REGISTRATIN_SHORTCODE, 'register_user_html');
   add_shortcode(MLM_VIEW_NETWORK_SHORTCODE, 'viewBinaryNetwork');
   add_shortcode(MLM_VIEW_GENEALOGY_SHORTCODE, 'viewBinaryNetwork');
   add_shortcode(MLM_NETWORK_DETAILS_SHORTCODE, 'mlmNetworkDetails');
   add_shortcode(MLM_UPDATE_PROFILE_SHORTCODE, 'mlm_update_profile');
   add_shortcode(MLM_CHANGE_PASSWORD_SHORTCODE, 'mlm_change_password');
   add_shortcode(MLM_MY_PAYOUTS_SHORTCODE, 'mlm_my_payout');
   add_shortcode(MLM_MY_PAYOUT_DETAILS_SHORTCODE, 'mlm_my_payout_details');
   
   
   /**** create pages for run payout and bonus ***/
   add_shortcode(MLM_DISTRIBUTE_COMMISSION_SHORTCODE, 'mlmDistributeCommission');
   add_shortcode(MLM_DISTRIBUTE_BONUS_SHORTCODE, 'mlmDistributeBonus');
   /***** end code for distributing payout and bonus*****/
	
   add_shortcode(MLM_MY_CHILD_MEMBER_DETAILS_SHORTCODE, 'mlm_my_child_member_details');
   
   add_shortcode(MLM_PAYMENT_STATUS_SHORTCODE, 'mlm_payment_status_details');
   add_shortcode(MLM_FINANCIAL_DASHBOARD_SHORTCODE, 'mlm_my_financial_dashboard');
   
   add_shortcode(MLM_EPIN_UPDATE_SHORTCODE, 'mlmePinUpdate');
   add_shortcode(JOIN_NETWORK_SHORTCODE, 'join_network');
}


function mlm_payment_status_details()
{

	if(isMLMLic()){
	    ob_start();
		mlm_payment_status_details_page();
		return ob_get_clean();
	}else{
		invalidMLMLic();
	}

}

function mlm_my_payout()
{

	if(isMLMLic()){
	    ob_start();
		mlm_my_payout_page();
		return ob_get_clean();
	}else{
		invalidMLMLic();
	}

}

function mlm_my_payout_details()
{

	if(isMLMLic()){
	    ob_start();
		mlm_my_payout_details_page();
		return ob_get_clean();
	}else{
		invalidMLMLic();
	}

}

/************ Register New User *************/
function register_user_html()
{

	if(isMLMLic()){
	    ob_start();
		register_user_html_page();
        return ob_get_clean();
	}else{
		invalidMLMLic();
	}

}
/************ Register New User *************/


function mlm_my_child_member_details()
{

	if(isMLMLic()){
		mlm_my_child_member_details_page();
		return ob_get_clean();
	}else{
		invalidMLMLic();
	}

}
/************ Update Profile *************/
function mlm_update_profile()
{

	if(isMLMLic()){
		ob_start();
		mlm_update_profile_Page();
		return ob_get_clean();
	}else{
		invalidMLMLic();
	}

}
/************ Update Profile  *************/



/************ Change Password *************/
function mlm_change_password()
{

	if(isMLMLic()){
			ob_start();
		mlm_change_password_Page();
		return ob_get_clean();
	}else{
		invalidMLMLic();
	}

}
/************ Change Password *************/

/************ Dashboard*************/
function mlmNetworkDetails()
{

	if(isMLMLic()){
	ob_start();
		mlmNetworkDetailsPage();
		return ob_get_clean();
	}else{
		invalidMLMLic();
	}

}
/************ Dashboard*************/

/************ Network & Genealogy Page *************/
function viewBinaryNetwork()
{
	if(isMLMLic()){
	ob_start();
		viewBinaryNetworkPage();
		return ob_get_clean();
	}else{
		invalidMLMLic();
	}

}
/************ Network & Genealogy Page *************/

/************Financial Dashboard *************/
function mlm_my_financial_dashboard()
{
	if(isMLMLic()){
	    ob_start();
		mlm_my_financial_dashboard_page();
		return ob_get_clean();
	}else{
		invalidMLMLic();
	}

}
/************Financial Dashboard*************/

/************ ePin Update *************/
function mlmePinUpdate()
{
	if(isMLMLic()){
	 ob_start();
		mlmePinUpdatePage();
		return ob_get_clean();
	}else{
		invalidMLMLic();
	}

}
/************ ePin Update *************/

/************ Join Network *************/
function join_network()
{
	if(isMLMLic()){
	 ob_start();
		join_network_page();
		return ob_get_clean();
	}else{
		invalidMLMLic();
	}

}
/************ Join Network *************/


function adminMLM_PO_editor()
{

	if(isMLMLic()){
		po_file_editor();
	}else{
		invalidMLMLic_poeditor();
	}

}
function mlm_admin_menu() 
{
		/*
		1st argument: Title of the page
		2nd argument: Name of the menu
		3rd argument: The capability required for this menu to be displayed to the user.
		if 3rd arugment value is zero then this menu also accessible at user interface
		4ht argument: pass to the URL (name of the page)
		5th argument: function name (which function to be called) 
		*/
		
	//add_menu_page() function add the new menu item
	//add_menu_page('WP-MLM-Settings', 'WP-MLM', 1,'register-first-user', 'register_first_user');
	//add_submenu_page('register-first-user', 'Admin Settings', 'Admin Settings', 1,'admin-settings', 'adminMLMSettings');
	
	$icon_url =  plugins_url()."/".MLM_PLUGIN_NAME."/images/mlm_tree.png";
	$icon_report =  plugins_url()."/".MLM_PLUGIN_NAME."/images/mlm_reports.png";
	add_menu_page('WP-MLM-Settings', __('Unilevel MLM','unilevel-mlm-pro'), 'manage_options','admin-settings', 'adminMLMSettings', $icon_url);
	add_submenu_page('admin-settings',__('Settings','unilevel-mlm-pro'),__('Settings','unilevel-mlm-pro'),'manage_options','admin-settings','adminMLMSettings');
	add_submenu_page('admin-settings',__('Run Payouts','unilevel-mlm-pro'),__('Run Payouts','unilevel-mlm-pro'),'administrator','mlm-payout','adminMLMPayout');
	add_submenu_page('admin-settings',__('User Report','unilevel-mlm-pro'),__('User Report','unilevel-mlm-pro'),'administrator','mlm-user-account','adminMLMUserAccount');
	add_submenu_page('admin-settings',__('Withdrawals','unilevel-mlm-pro'),__('Withdrawals','unilevel-mlm-pro'),'administrator','admin-mlm-pending-withdrawal','adminMLMUserWithdrawals');
	add_submenu_page('admin-settings',__('Payment Settings','unilevel-mlm-pro'),__('Payment Settings','unilevel-mlm-pro'),'administrator','admin-mlm-payment-settings','adminPaymentSettings');
        add_submenu_page('admin-settings',__('Reports','unilevel-mlm-pro'),__('Reports','unilevel-mlm-pro'),'administrator','admin-reports','adminReports');	

	add_submenu_page('admin-settings',__('User Withdrawal Process','unilevel-mlm-pro'),'','administrator','admin-mlm-withdrawal-process','adminMLMUserWithdrawalsProcess');

		
}

		
function adminMLMUserWithdrawalsProcess(){
	if(isMLMLic()){
		mlm_withdrawal_process();
	}else{
		invalidMLMLic();
	}		
}

function adminMLMSucessWithdrawals(){
	if(isMLMLic()){
		mlm_withdrawal_sucess();
	}else{
		invalidMLMLic();
	}		
}

function adminPaymentSettings() {
	if(isMLMLic()){
		mlm_payment_settings();
	}else{
		invalidMLMLic();
	}		
}


function adminMLMUserWithdrawals(){
	if(isMLMLic()){
		mlm_withdrawal_request();
	}else{
		invalidMLMLic();
	}		
}

function adminMLMUserAccount()
{
	if(isMLMLic()){
		adminMLMUserAccountInterface();
	}else{
		invalidMLMLic();
	}		

}

/************ePins reports *************/
function adminMLMePinsReports()
{
	if(isMLMLic()){
		mlm_ePins_reports();
	}else{
		invalidMLMLic();
	}
}
/************ePins reports *************/


// get_post_id function return the inserted post_id's
function get_post_id($page)
{
	global $wpdb;		
	$table_prefix = mlm_core_get_table_prefix();
	$sql = "SELECT post_id 
								FROM {$table_prefix}postmeta 
								WHERE meta_key = '".$page."' 
							 AND meta_value = '".$page."'";
	$post_id = $wpdb->get_var($sql);
	return $post_id;
}
function get_post_id_or_postname($page)
{
	global $wpdb;		
	$table_prefix = mlm_core_get_table_prefix();
	$mlm_settings = get_option('permalink_structure');
	$sql = "SELECT post_id 
								FROM {$table_prefix}postmeta 
								WHERE meta_key = '".$page."' 
							 AND meta_value = '".$page."'";
	$post_id = $wpdb->get_var($sql);
	if($mlm_settings == '/%postname%/')
	{
			$post_name = $wpdb->get_var("SELECT post_name FROM {$table_prefix}posts WHERE id = $post_id");
			return bloginfo('url')."/".$post_name;	
	}
	return bloginfo('url')."/?page_id=$post_id";
}


function get_post_id_or_postname_for_payout($page, $id)
{
	global $wpdb;		
	$table_prefix = mlm_core_get_table_prefix();
	$mlm_settings = get_option('permalink_structure');
	$sql = "SELECT post_id 
								FROM {$table_prefix}postmeta 
								WHERE meta_key = '".$page."' 
							 AND meta_value = '".$page."'";
	$post_id = $wpdb->get_var($sql);
	if($mlm_settings == '/%postname%/')
	{
			$post_name = $wpdb->get_var("SELECT post_name FROM {$table_prefix}posts WHERE id = $post_id");
			return bloginfo('url')."/".$post_name."/?pid=$id";	
	}
	return bloginfo('url')."/?page_id={$post_id}&pid=$id";
}

//register_page function register the page and postID
/*function register_page($title, $content)
{
	$post_array = array(
							'post_title'    =>  $title,
							'post_content'	 => "[".$content."]",
							'post_status'   =>  'publish',
							'post_type'	 =>  'page',
							'comment_status'=>  'close',
							'ping_status'	 =>  'close'
						);
	// Insert the post into the wp_posts table
	$post_id = wp_insert_post( $post_array );
	return $post_id;
}*/

function register_page($new_page_title, $new_page_content){

	$new_page_template = '';
        $new_page_id='';
	$page_check = get_page_by_title($new_page_title);
    $new_page = array(
        'post_type' => 'page',
        'post_title' => $new_page_title,
        'post_content' => "[".$new_page_content."]",
        'post_status' => 'publish',
        'post_author' => 1,
		'ping_status'	 =>  'close'
    );
    if(!isset($page_check->ID)){
        $new_page_id = wp_insert_post($new_page);
        if(isset($new_page_template)){
            update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
        }
    }
	return $new_page_id;
}


function createTheMlmMenu()
{ 
	//assign the mlm page title to the array
	$page_title = array();
	$page_title['registration'][] = MLM_REGISTRATION_TITLE;
	
	$page_title['network'][] = MLM_VIEW_NETWORK_TITLE;
	$page_title['network'][] = MLM_NETWORK_DETAILS_TITLE;
	$page_title['network'][] = MLM_VIEW_GENEALOGY_TITLE;
	$page_title['network'][] = MLM_MY_PAYOUTS;
    $page_title['network'][] = MLM_FINANCIAL_DASHBOARD_TITLE;
	
	//$page_title['profile'][] = MLM_UPDATE_PROFILE_TITLE;
	$page_title['profile'][] = MLM_CHANGE_PASSWORD_TITLE;
	$page_title['profile'][] = MLM_EPIN_UPDATE_TITLE;
	
	$page_title['joinmlm'][] = JOIN_NETWORK;
	//$page_title['commission'][] = MLM_DISTRIBUTE_COMMISSION_TITLE;
//	$page_title['commission'][] = MLM_DISTRIBUTE_BONUS_TITLE;
	
	//name of the menu
	$name = MENU_NAME;
	
    //create the menu
    $menu_id = wp_create_nav_menu($name);
	
	//get the term id
 	$menu = get_term_by( 'name', $name, 'nav_menu' );
	
 	foreach($page_title as $value)
	{
		//get the post_id by the page title
		$myPage = get_page_by_title($value[0]);
		
		//build the menu item array
		$args = array();
		$args['menu-item-db-id'] = 0;
		$args['menu-item-object-id'] = $myPage->ID;
		$args['menu-item-object'] = 'page';
		$args['menu-item-parent-id'] = 0;
		$args['menu-item-position'] ='';
		$args['menu-item-type'] = 'post_type';
		$args['menu-item-title'] = $value[0];
		$args['menu-item-description'] = '';
		$args['menu-item-status'] = 'publish';
		$args['menu-item-attr-title'] = '';
		$args['menu-item-target'] = '';
		$args['menu-item-classes'] = '';
		$args['menu-item-xfn'] = '';
		
		//create the menu item
		$menu_item_id = wp_update_nav_menu_item($menu->term_id, 0, $args);
		
		if(count($value) > 1)
		{
			for($i = 1; $i < count($value); $i++)
			{
				//get the post_id by the page title
				$myPage = get_page_by_title($value[$i]);
				
				//build the menu item array
				$args = array();
				$args['menu-item-db-id'] = 0;
				$args['menu-item-object-id'] = $myPage->ID;
				$args['menu-item-object'] = 'page';
				$args['menu-item-parent-id'] = $menu_item_id;
				$args['menu-item-position'] ='';
				$args['menu-item-type'] = 'post_type';
				$args['menu-item-title'] = $value[$i];
				$args['menu-item-description'] = '';
				$args['menu-item-status'] = 'publish';
				$args['menu-item-attr-title'] = '';
				$args['menu-item-target'] = '';
				$args['menu-item-classes'] = '';
				$args['menu-item-xfn'] = '';
				
				//create the menu item
				$item_id = wp_update_nav_menu_item($menu->term_id, 0, $args);
			}
		}
	}
	update_option('menu_check', true);
	
	$primary_menu = array
					(
						"nav_menu_locations" => array
							(
								"primary" => $menu_id,
								"primary_".PLUGIN_NAME => 0
							)
					
					);
	$theme_slug = get_option( 'stylesheet' );
	update_option("theme_mods_$theme_slug", $primary_menu);
}


function add_payment_status_column_value( $value, $column_name, $user_id ){
	global $wpdb;
	//$user = get_userdata( $user_id );
		
	/***************************/
	if ( 'payment_status' == $column_name)
	{
		
		$table_prefix = mlm_core_get_table_prefix();
		/*check that it is mlm user or not */
		$res = $wpdb->get_row("SELECT user_id, payment_status FROM {$table_prefix}mlm_users WHERE user_id = '".$user_id."'");
		$html = '';
		
		if($wpdb->num_rows > 0)
		{
			
			$path = "'".plugins_url()."/".MLM_PLUGIN_NAME."'";
			
			$currStatus = $res->payment_status;
			global $paymenntStatusArr; 
			
			$html .= '<select name="payment_status_'.$user_id.'" id="payment_status_'.$user_id.'" onchange="update_payment_status('.$path.','.$user_id.',this.value)">';
			
			foreach($paymenntStatusArr AS $row=>$val) :	
			
			if($row == $currStatus ){
				$sel = 'selected="selected"';
			}else{
				$sel ='';
			}
			
			$html .= '<option value="'.$row.'" '.$sel.'>'.$val.'</option>';
			endforeach; 
			$html .= '</select><span id="resultmsg_'.$user_id.'"></span>';
			return $html;
		
		
		}else{
			return "Not a MLM User";
		}	
		
	}
	if ( 'sponsor_username' == $column_name)
	{
	
	$table_prefix = mlm_core_get_table_prefix();
	
		/*check that it is mlm user or not */
		$res = $wpdb->get_row("SELECT username FROM {$table_prefix}mlm_users WHERE  user_key IN(select parent_key as user_key FROM {$table_prefix}mlm_users WHERE user_id = '".$user_id."')");
		
		
if($wpdb->num_rows > 0)
{
$name=$res->username;	
return $name;
}
else
{
return "---";
}	
	
	}
	
	if ('ePin' == $column_name){
			global $wpdb;
			$table_prefix = mlm_core_get_table_prefix();
			$user = get_userdata( $user_id );
			 $user_key = $wpdb->get_var("select user_key from {$table_prefix}mlm_users where user_id='{$user->ID}'");
			/*check that it is mlm user or not */
			$res = $wpdb->get_row("SELECT epin_no FROM {$table_prefix}mlm_epins WHERE user_key = '".$user_key."'");
			$path = "'".plugins_url()."/".MLM_PLUGIN_NAME."'";
                        
			if($wpdb->num_rows > 0)
			{
								
				return $res->epin_no;
					
			}
                        else
                        {
                            $not_mlm = $wpdb->get_row("select id from {$table_prefix}mlm_users where user_id='{$user->ID}'");
                            if($wpdb->num_rows=='0')
                            {
                                return 'Not MLM User';     
                            }
							else 
							{      
							
								$payment_status = $wpdb->get_var("select payment_status from {$table_prefix}mlm_users where user_id='{$user->ID}'");
								if($payment_status=='1')
								{
								echo '';
								}
								else if($payment_status=='2')
								{
								echo '';
								}
								else
								{
								$epin='<input type="text" name="epin" id="epin_'.$user_id.'"><input type="button" value="Update ePin" id="update_'.$user_id.'" onclick="setePinUser('.$path.','.$user_id.',document.getElementById(\'epin_'.$user_id.'\').value);"><span id="epinmsg_'.$user_id.'"></span>';
								return $epin;
								 }
							}
                        }
			
			
		}
	
}


function load_javascript() {
	wp_enqueue_script( 'custom-script', plugins_url( '/js/ajax.js', __FILE__ ));
	wp_enqueue_script( 'custom-script-epoch-classes', plugins_url( '/js/epoch_classes.js', __FILE__ ));
	wp_enqueue_script( 'custom-script-form-validation', plugins_url( '/js/form-validation.js', __FILE__ ));
	wp_enqueue_script( 'custom-cookie-script', plugins_url( '/js/jquery.cookie.js', __FILE__ ));
	wp_enqueue_script( 'custom-script-settings-pages', plugins_url( '/js/admin-script.js', __FILE__ ));
	wp_enqueue_style('custom-css-epoch-classes', plugins_url( '/css/epoch_styles.css', __FILE__ ));
	wp_enqueue_style('custom-css-mlm', plugins_url( '/css/mlm.css', __FILE__ ));
	
	
}

//Making jQuery Google API
function modify_jquery() {
	if (!is_admin()) {
		// comment out the next two lines to load the local copy of jQuery
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js', false, '1.8.1');
		wp_enqueue_script('jquery');
	}
}
add_action('init', 'modify_jquery');

/*function to display the payment status column in the users table*/
add_filter('manage_users_columns', 'add_payment_status');
add_filter('manage_users_custom_column',  'add_payment_status_column_value', 10, 3);

function add_payment_status( $columns){

    $columns['sponsor_username'] = __('Sponsor', 'sponsor_username');
    $columns['payment_status'] = __('Payment Status', 'payment_status');
	$mlm_settings = get_option('wp_mlm_general_settings');
    if($mlm_settings['ePin_activate']==1)
    $columns['ePin'] = __('ePin', 'ePin');

    return $columns;
}
 
 
 function my_payout_function($id='')
{
	
	global $table_prefix;
	
	global $wpdb;
	global $current_user;
    get_currentuserinfo();
	
if($id == "")	
	$userId = $current_user->ID;
else 
	$userId = $id; 
	//$mlm_user_id = $wpdb->get_results( );
	
	$sql = "SELECT {$table_prefix}mlm_users.id AS id FROM {$table_prefix}users,{$table_prefix}mlm_users WHERE {$table_prefix}mlm_users.username = {$table_prefix}users.user_login AND {$table_prefix}users.ID = '".$userId."'"; 
	
	$res = $wpdb->get_results($sql, ARRAY_A); 
	
	if(!empty($res)) $mlm_user_id = $res[0]['id']; 
	else  $mlm_user_id ='';
			
	if ( is_user_logged_in())
	{
	
		$sql = "SELECT id, user_id, DATE_FORMAT(`date`,'%d %b %Y') as payoutDate, payout_id, commission_amount, bonus_amount,total_amt FROM {$table_prefix}mlm_payout WHERE user_id = '".$mlm_user_id."' ORDER BY UNIX_TIMESTAMP(`date`) DESC";

		$myrows = $wpdb->get_results($sql);
	
	}
	
	return $myrows; 

}

function isMLMLic()
{

	$siteUrl = 'http';
	if(!empty($_SERVER["HTTPS"])){
	if ($_SERVER["HTTPS"] == "on") {$siteUrl .= "s";} }
		$siteUrl .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$siteUrl .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	} else {
		$siteUrl .= $_SERVER["SERVER_NAME"];
	}	
	$licKeyArr = get_option('mlm_license_settings');
    $packageName = MLM_PLUGIN_NAME;
	
	$params = 'k='.$licKeyArr['license_key'].'&d='.$siteUrl.'&p='.$packageName;  //echo $params;
	$url = 'wpbinarymlm.com/Auth/index.php';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"http://$url"); 
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch) ;
	//echo 'error'. curl_error($ch);	
	//$info = curl_getinfo($ch); echo "<pre>";print_r($result); echo '</pre>';
	curl_close($ch);
	return $result; 
	
	//return true; 
}


function invalidMLMLic()
{
	$msg = '<br><br><br><div class="updated fade"><p><strong>
	You have not activated the License Key for the plugin. In order to use the features on this page please apply for a license key at <a href="http://wpbinarymlm.com/unilevel-mlm-pro" target="_blank">WPBinaryMLM.com</a>, if you don\'t have one. If you already have a license key please click <a href="http://wpbinarymlm.com/faqs/" target="_blank">Here</a> to get details on how to obtain and input your license key in the plugin.</strong></p></div>';
	 _e($msg); 
}

function invalidMLMLic_poeditor()
{
	$msg = '<br><br><br><div class="updated fade"><p><strong>
	You have not activated the License Key for the plugin. In order to use the features on this page please apply for a license key at <a href="http://wpbinarymlm.com/unilevel-mlm-pro" target="_blank">WPBinaryMLM.com</a>, if you don\'t have one. If you already have a license key please click <a href="http://wpbinarymlm.com/faqs/" target="_blank">Here</a> to get details on how to obtain and input your license key in the plugin.</strong></p></div>';

	_e($msg); 
}

function licUpdate($licArr)
{
	update_option('mlm_license_settings', $licArr);
	
	if(isMLMLic()){			
		return '<div class="notibar msgsuccess"><a class="close"></a><p>'.__('Your License key has been updated.','unilevel-mlm-pro').'</p></div>';
	}else{
		return '<div class="notibar msgalert"><a class="close"></a><p>'.__('Sorry, Your License key is invalid.','unilevel-mlm-pro').'</p></div>';
	}	
}



function siteURL()
{
	$siteName = 'http';
	if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$siteName .= "s";}
		$siteName .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$siteName .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	} else 
	{
		$siteName .= $_SERVER["SERVER_NAME"];
	}
	
	return $siteName;

}

function payoutRun()
{
	if(isMLMLic()){
		$returnArr['displayData'] = wpmlm_run_pay_display();
		$returnArr['directRun'] = '';
		$returnArr['msgforpro'] = '';
		
	}else{
		$returnArr['displayData'] = ''; 
		$returnArr['directRun'] = '';
		$returnArr['msgforpro'] = payoutLicMsg();
	}
	
	return $returnArr; 
	
}


function payoutLicMsg()
{
	if(isMLMLic()){
		return '';
	}else{
	
		return _e("<p style='border:solid 1px #E6DB55; background:#FFFFCC; padding:10px; margin:10px;'>In order to view the details of the payout (like the image below) before committing the same, you may consider upgrading to the PRO version of the plugin. You can purchase a license key at",'unilevel-mlm-pro'). '<a href="http://wpbinarymlm.com/unilevel-mlm-pro/">WordpressMLM</a>.
<br /><br /><img src="'.MLM_URL.'/images/payout-view.png" width="715" align="absmiddle" /><br /><br /></p>'; 	
	}

}

function redirectPage($actionPage,$hiddenFldValArr) {
			
		$hiddenFldStr = "";
		if(is_array($hiddenFldValArr) && count($hiddenFldValArr)>0) { 
			while(list($k,$v) = each($hiddenFldValArr)) { 
				if($v!="") 
				$hiddenFldStr .="<input type='hidden' name='$k' value='".htmlspecialchars($v,ENT_QUOTES)."'>\n";
			}
		}
		//echo $hiddenFldStr;exit;
		//echo $actionPage;
		//echo "file here 123";exit;
		?>
		<HTML>
		<HEAD>
		<TITLE></TITLE>
		</HEAD>
		<BODY>
		<FORM NAME='frm' METHOD='POST' ACTION="<?php _e($actionPage);?>">
		<?php  _e($hiddenFldStr);?>
		</FORM>
		<SCRIPT language="javascript" type="text/javascript">document.frm.submit();</SCRIPT>
		</BODY>
		</HTML>
		<?php 
		//echo "control here";exit;
		exit;
	}

	//return the logeed user's fa_user key
function get_top_level_user()
{
	$table_prefix = mlm_core_get_table_prefix();
	
	global $wpdb;
	
    $num = $wpdb->get_var("SELECT username FROM {$table_prefix}mlm_users WHERE parent_key = '0' AND sponsor_key = '0'");


	return $num;
}

// count all member where parent_key is $id
function countLevelMember($id)
{
	$table_prefix = mlm_core_get_table_prefix();
	
	global $wpdb;
	
    $num = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_users WHERE parent_key IN(".$id.")");


	return $num;


}

// Made array for  all member where parent_key is $id
function returnMemberUserkey($id)
{
	$table_prefix = mlm_core_get_table_prefix();
	
	global $wpdb;
	
    $num = $wpdb->get_var("SELECT GROUP_CONCAT(user_key) as num FROM {$table_prefix}mlm_users WHERE parent_key IN(".$id.")");


	return $num;


}

// Made array for  all member where parent_key is $id
function returnMemberParentkey($id)
{
	$table_prefix = mlm_core_get_table_prefix();
	
	global $wpdb;
	
    $num = $wpdb->get_var("SELECT parent_key FROM {$table_prefix}mlm_users WHERE user_key = '".$id."'");


	return $num;


}
// count all member where parent_key is $id
function returncountLevelMember($id,$level)
{
	$table_prefix = mlm_core_get_table_prefix();
	
	global $wpdb;
	
    $num = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_hierarchy WHERE pid=".$id." AND level=".$level."");


	return $num;


}

// count all member where parent_key is $id
function getLevelInfo($id,$level)//third parameter$sponser
{
	$table_prefix = mlm_core_get_table_prefix();
	
	global $wpdb;
	
    $num = mysql_query("SELECT cid FROM {$table_prefix}mlm_hierarchy WHERE pid=".$id." AND level=".$level."");
    
	while($result=mysql_fetch_array($num))
    {
	$user=array();

	$usr_dtls=mysql_fetch_array(mysql_query("SELECT user_id,username,sponsor_key,payment_status FROM {$table_prefix}mlm_users WHERE user_key='".$result['cid']."'"));
	$user['username']=$usr_dtls['username'];
	$user['first_name']=get_user_meta($usr_dtls['user_id'], 'first_name', true);
	$user['last_name']=get_user_meta($usr_dtls['user_id'], 'last_name', true);
	$sponser_id = $usr_dtls['sponsor_key'];
	
	$user['sponsor']=getusernamebykey($sponser_id);
	$email=$wpdb->get_var("SELECT user_email FROM {$table_prefix}users WHERE ID='".$usr_dtls['user_id']."'");
    $user['email']=$email;
        if($usr_dtls['payment_status']==1)
	{    $user['status']="Paid";      }
	else {     $user['status']="Not Paid";   }
	$user_data[]=$user;
	}
	return $user_data;


}
//Function to get date/time stamp as required by the gateway
function gatewaydatetime() {
  $str=date('Y-m-d H:i:s P');
  return $str;
}                 
//Function to remove invalid characters / characters which will cause the gateway to error.
function stripGWInvalidChars($strToCheck) {
	$toReplace = array("#","\\",">","<", "\"", "[", "]");
	$cleanString = str_replace($toReplace, "", $strToCheck);
	return $cleanString;
}
//Function to generate a unique OrderID for the transaction (The OrderID can be any AlphaNumeric string - e.g. your own carts order ID if applicable
function guid(){
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
                .substr($charid, 0, 4).$hyphen
                .substr($charid, 4, 4)
                .chr(125);// "}"
        return $uuid;
    }
}

function UserStatusUpdate($userId)
{
    global $wpdb;		
	$table_prefix = mlm_core_get_table_prefix();
	


	
  //get no. of level
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
	$product_value=$mlm_general_settings['single-sale'];
	$mlm_settings = get_option('wp_mlm_payout_settings');
	$mlm_eligibility = get_option('wp_mlm_eligibility_settings');
	
	$type1=0;
	$type2=0;
	$type3=0;
	    


		$sql = "UPDATE {$table_prefix}mlm_users  SET `payment_status` = '1' WHERE user_id = '".$userId."'";
		
		$wpdb->query($sql);
	
	
	
		$id = $wpdb->get_var("SELECT id FROM {$table_prefix}mlm_users WHERE `user_id` = '".$userId."'");	
		
		// Update Payment status of user in Payment_status table 
		$wpdb->query("UPDATE {$table_prefix}mlm_payment_status SET payment_status = '1' WHERE user_id = '".$id."'");
		
		
	
	
      // Get If User have Reffered any user
	$results = $wpdb->get_results("SELECT user_key FROM {$table_prefix}mlm_users WHERE banned = '0' AND payment_status = '1' AND user_id = '".$userId."'");	
	
	
	//Get Registered User's Sponser Id AND User Key
	$usr_dtls=mysql_fetch_array(mysql_query("SELECT sponsor_key,user_key FROM {$table_prefix}mlm_users WHERE user_id='".$userId."'"));

//Get Registered USer's PArent Payment Status	
$check_paid=$wpdb->get_var("SELECT payment_status FROM {$table_prefix}mlm_users WHERE user_key='".$usr_dtls['sponsor_key']."'");	


$per_num = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_users WHERE sponsor_key='".$usr_dtls['sponsor_key']."' AND payment_status='1'");
	
	// Check if Direct Refferal Field is Not empty And Parent Is PAID  then PARENT Will EARN Direct Commission
	$company_amt='';

//	if(($mlm_settings['referral_commission_amount']>0) && ($per_num>=$mlm_eligibility['personal_referrer']))

if($mlm_settings['referral_commission_amount']>0) {
	
$date = date("Y-m-d H:i:s");
$parentId=$usr_dtls['sponsor_key'];
$childIds=$usr_dtls['user_key'];
$amt=$mlm_settings['referral_commission_amount'];
$type1=$amt;

//if user will be PAid first Time the Refferal Commission will be insert in the Table

$insert = $wpdb->query("
                        INSERT INTO {$table_prefix}mlm_commission 
                        (
                                                id, date_notified, parent_id, child_ids, amount, comm_type
                        ) 
                        VALUES 
                        (
                                                        NULL, '".$date."', '".$parentId."', '".$childIds."', '".$amt."', '2'										
                        )
");	


	
	}
	
	//Direct Refferral Commission to Company
	if($mlm_settings['company_commission_amount'] >0) 
	{
	
$date = date("Y-m-d H:i:s");
$top=get_top_level_user();
$pId=get_user_key_admin($top);
$cIds=$usr_dtls['user_key'];
$amts=$mlm_settings['company_commission_amount'];
$type2=$amts;
$wpdb->query("INSERT INTO {$table_prefix}mlm_commission 
																											(id, date_notified, parent_id, child_ids, amount, comm_type
																											) 
																											VALUES 
																											(
																															NULL, '".$date."', '".$pId."', '".$cIds."', '".$amts."', '3'										
																											)
																							");		
	
	}
	
	
	//Level Commission Paid User's Parent & other Levels
	for($i=1;$i<=$mlm_no_of_level;$i++)
	{
	
	$date = date("Y-m-d H:i:s");
	$amount=$mlm_settings['level'.$i.'_commission'];
	$child_ids=$usr_dtls['user_key'];
	
	$is_user=$wpdb->get_var("SELECT pid FROM {$table_prefix}mlm_hierarchy WHERE cid='".$usr_dtls['user_key']."' AND level='".$i."'");
		
	if($is_user)
	{
	$parent_ids=$is_user;
	
	$tot_ref = $wpdb->get_var("SELECT COUNT(*) AS num FROM {$table_prefix}mlm_users WHERE sponsor_key=".$parent_ids." AND payment_status=1");
	if($tot_ref >= $mlm_eligibility['personal_referrer']) 
{
$type3=$type3+$amount;
$wpdb->query("INSERT INTO {$table_prefix}mlm_commission (id, date_notified, parent_id, child_ids, amount, comm_type) VALUES (NULL, '".$date."', '".$parent_ids."', '".$child_ids."', '".$amount."','1')");	

}
else 
{

$company_amt=$company_amt+$amount;

}
	
	
	}
	else 
	{
	$company_amt=$company_amt+$amount;
	
	}
	
	
	}
	// Left Commission Amount to Company
	if($company_amt>0) 
	{
	
$date = date("Y-m-d H:i:s");
$top=get_top_level_user();
$pId=get_user_key_admin($top);
$cIds=$usr_dtls['user_key'];


$total=$type1+$type2+$type3+$company_amt;

$left_over=$product_value-$total;

$amts=$company_amt+$left_over;

$wpdb->query("INSERT INTO {$table_prefix}mlm_commission (id, date_notified, parent_id, child_ids, amount, comm_type) VALUES (NULL, '".$date."', '".$pId."', '".$cIds."', '".$amts."','4')");	
	
	}
	
	return true;

}


function DefaultDateFormat($date)
{
global $wpdb;
$date_format = get_option( 'date_format' );

$newFormat=date($date_format, strtotime($date));

return $newFormat;
}

/**************** this function for the get user_id based on epin user key *********************/

function getuseruidbykey($key)
{
	$table_prefix = mlm_core_get_table_prefix();
	global $wpdb;
	$id = $wpdb->get_var("
							SELECT user_id 
							FROM {$table_prefix}mlm_users 
							WHERE `user_key` = '".$key."'
				");
	return $id;
}

/******************* end ****************/

/********* Genaral settings ePin Functionality ***************/
function general_settings_epin()
{
        global $wpdb;	
	//get database table prefix
	$table_prefix = mlm_core_get_table_prefix();
        $mlm_settings = get_option('wp_mlm_general_settings');
   if(isMLMLic()){            
?>              <table>
                <tr>
			<th scope="row" class="admin-settings">
				<strong><?php _e('Activate ePin','unilevel-mlm-pro');?> <span style="color:red;">*</span>:</strong>
			</th>
			<td>
			<?php if($mlm_settings['ePin_activate']=='0') { ?>
		<script>	jQuery(document).ready(function () { jQuery(".sole_id").hide(); });</script>
		<?php } ?>
		
		
			<input class="radio" type="radio" name="ePin_activate" value="1" <?php if(isset($mlm_settings['ePin_activate'])&&$mlm_settings['ePin_activate']=='1'){echo 'checked';}?>/> <?php _e('Yes', 'unilevel-mlm-pro');?>
			<?php $sql = "SELECT COUNT( * ) AS ps FROM  {$table_prefix}mlm_users WHERE  `payment_status` =  '2'";
			$ps=$wpdb->get_var($sql);
			$sql1 = "SELECT COUNT( * ) AS es FROM  {$table_prefix}mlm_epins WHERE  `status` =  '1'";
			$es=$wpdb->get_var($sql1);
			if($ps>0 || $es>0)
			{
			echo "<br>".__('Cannot be disabled as 1 or more ePins have been used for registration.','unilevel-mlm-pro');
			}
			else
			{
			?>
			<input class="radio" type="radio" name="ePin_activate" value="0" <?php if(isset($mlm_settings['ePin_activate'])&&$mlm_settings['ePin_activate']=='0'){echo 'checked';}?>/> <?php _e('No', 'unilevel-mlm-pro');?>
			<?php }?>
			</td>
		</tr>
		<tr class="sole_id" >
			<th scope="row" class="admin-settings">
				<strong><?php _e('Sole Payment Method','unilevel-mlm-pro');?> <span style="color:red;">*</span>:</strong>
			</th>
			<td>
			<input class="radio" type="radio" name="sol_payment" value="1" <?php if(isset($mlm_settings['sol_payment'])&&$mlm_settings['sol_payment']=='1'){echo 'checked';}?>/> <?php _e('Yes', 'unilevel-mlm-pro');?>
			<input class="radio" type="radio" name="sol_payment" value="0" <?php if(isset($mlm_settings['sol_payment'])&&$mlm_settings['sol_payment']=='0'){echo 'checked';}?>/> <?php _e('No', 'unilevel-mlm-pro');?>
			</td>
		</tr>
		<tr class="sole_id">
				<td><strong><?php _e('ePin Length','unilevel-mlm-pro');?></strong></td>
				
				<td>
				<?php $epin_length= $mlm_settings['epin_length']?>
				<select name="epin_length" id="epin_length">
				<?php
					// or whatever you want
					$epin_array=array(8 => '8',9 => '9',10 => '10',11 =>'11',12 =>'12',13 =>'13', 14=>'14', 15=>'15');
					foreach( $epin_array as $key => $val)
					{
						?>
						<option value="<?php echo $key; ?>"<?php if($key==$epin_length) echo ' selected="selected"';?>>
						<?php echo $val; ?>
						</option>
						<?php
					}
				?>
				
				</select>
				</td>
				
			</tr></table>
     <?php
     }else{
		invalidMLMLic();
                }
}
 
                
/************ epin genaral Settings *******************/


/********** epin settings tab ****************/
function epin_tab()
{
     if(isMLMLic()){ 
                mlm_epin_settings();
            }else{
                 invalidMLMLic();
             }
}

/********** epin settings tab ****************/


function mlmUserUpdateePin($user_id,$epin)
{
    global $wpdb;
    $table_prefix = mlm_core_get_table_prefix();
    $pointResult = mysql_query("select point_status from {$table_prefix}mlm_epins where epin_no = '{$epin}'");
    $pointStatus = mysql_fetch_row($pointResult);
    // to epin point status 1 
    if($pointStatus[0]=='1')
    {	
	$paymentStatus='1'; 
    }
    // to epin point status 1 
    else if($pointStatus[0]=='0')
    {
	$paymentStatus = '2'; 
    }
	else
	{
	$paymentStatus = '0'; 
	}
    $sql="UPDATE {$table_prefix}mlm_users SET payment_status='{$paymentStatus}' WHERE user_id='{$user_id}'";
    if(mysql_query($sql))
	{return TRUE;}else {return FALSE;}
}

/********** Random ePin Genarate **************/	
function epin_genarate($no)
{
	
    /// Random characters
	$characters = array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

	// set the array
	$keys = array();

	// set length
	$length = $no;

	// loop to generate random keys and assign to an array
	while(count($keys) < $length) 
	{
		$x = mt_rand(0, count($characters)-1);
		if(!in_array($x, $keys)) 
       		$keys[] = $x;
	}

	// extract each key from array
	$random_chars='';
	foreach($keys as $key)
   		$random_chars .= $characters[$key];

	// display random key
	return $random_chars;
}

/********** Random ePin Genarate **************/


function isUpdate() {
    $date_activation = isMLMLic();
    $date_expire = date('Y-m-d H:i:s', strtotime($date_activation . ' + 365 days'));
    $current_date = date('Y-m-d H:i:s');
    if ($current_date < $date_expire)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function github_plugin_updater_mlm_init($githunconfig) {

    require_once(MLM_PLUGIN_DIR . '/mlm_core/updater.php');
    define('WP_GITHUB_FORCE_UPDATE', true);
    if (is_admin() && isUpdate()) {
        new WP_GitHub_Updater($githunconfig);
    }
}

?>