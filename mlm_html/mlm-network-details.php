<?php
function mlmNetworkDetailsPage()
{
	//get loged user's key
	$key = get_current_user_key();
	

	//Total my personal sales
	$personalSales = totalMyPersonalSales($key);
	
	//Total my personal sales active users
	$activePersonalSales = activeUsersOnPersonalSales($key);
	
	
	//show five users on personal sales
	$fivePersonalUsers = myFivePersonalUsers($key);
	
	//get logged in user info
		global $current_user, $wpdb;
	    $table_prefix = mlm_core_get_table_prefix();
	get_currentuserinfo();
	$username = $current_user->ID;

	$user_info = get_userdata($current_user->ID);
	$_SESSION['ajax'] = 'ajax_check';
	
	$add_page_id = get_post_id('mlm_registration_page');
	$sponsor_name = $current_user->user_login;
	
	    
		/**********Affiliate URL CODE***********************/
		
		$affiliateURLold = site_url().'?page_id='.$add_page_id.'&sp='.$key;
        $affiliateURLnew = site_url().'/u/'.$sponsor_name;
        
        $permalink = get_permalink( empty($_GET['page_id'])?'': $_GET['page_id']);
        $postidparamalink = strstr($permalink,'page_id' );
        $affiliateURL = ($postidparamalink)?$affiliateURLold:$affiliateURLnew;
		
		/*********E O F Affiliate URL Code************************/
	 
	 
	 $view_memberpage_id = $wpdb->get_var("SELECT id FROM {$table_prefix}posts  WHERE `post_content` LIKE '%mlm-view-child-level-member%'	AND `post_type` != 'revision'");
	
	       // Check Payment Method
	    $mlm_method = get_option('wp_mlm_payment_method');
	

	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
	$mlm_pay_settings = get_option('wp_mlm_payment_settings'); 
	 
    //Check If USER is Not PAid then will show option for Payment
	$check_paid = $wpdb->get_var("SELECT payment_status FROM {$table_prefix}mlm_users WHERE user_id = '".$username."'");
	
	if($check_paid==0)
	{
     PayNowOptions($username,'dashboard');
	}
		
?>
	
<p class="affiliate_url"><strong>Affiliate URL :</strong> <?= $affiliateURL ?> </p><br /> 
		<table width="100%" border="0" cellspacing="10" cellpadding="1">
		  <tr>
			<td width="40%" valign="top">
				<table width="100%" border="0" cellspacing="10" cellpadding="1">
				  <tr>
					<td colspan="2"><strong><?PHP _e('Personal Information','unilevel-mlm-pro');?></strong></td>
				  </tr>
				  <tr>
					<td scope="row"><?php _e('Title','unilevel-mlm-pro');?></td>
					<td><?PHP _e('Details','unilevel-mlm-pro');?></td>
				  </tr>
				  <tr>
					<td scope="row"><?PHP _e('Name','unilevel-mlm-pro');?></td>
					<td><?=$user_info->first_name.' '.$user_info->last_name ?></td>
				  </tr>
				  <tr>
					<td scope="row"><?PHP _e('Address','unilevel-mlm-pro');?></td>
					<td style="white-space:normal;"><?=$user_info->user_address1."<br>".$user_info->user_address2 ?></td>
				  </tr>
				  <tr>
					<td scope="row"><?PHP _e('City','unilevel-mlm-pro');?></td>
					<td><?=$user_info->user_city ?></td>
				  </tr>
				  <tr>
					<td scope="row"><?PHP _e('Contact No','unilevel-mlm-pro');?>.</td>
					<td><?=$user_info->user_telephone ?></td>
				  </tr>
				  <tr>
					<td scope="row"><?PHP _e('DOB','unilevel-mlm-pro');?></td>
					<td><?=$user_info->user_dob ?></td>
				  </tr>
				  
				  
				  
				  <tr>
		<td><a href="<?= get_post_id_or_postname('mlm_update_profile_page','unilevel-mlm-pro');?>" style="text-decoration: none"><?php _e('Edit','unilevel-mlm-pro');?></a></td>
		<td><a href="<?= get_post_id_or_postname('mlm_network_genealogy_page','unilevel-mlm-pro');?>" style="text-decoration: none"><?php _e('View Genealogy','unilevel-mlm-pro');?></a></td>
		</tr>
				</table> 
					<table width="100%" border="0" cellspacing="10" cellpadding="1">
				  <tr>
					<td colspan="2"><strong><?php _e('My Payouts','unilevel-mlm-pro');?></strong></td>
				  </tr>
				  <tr>
					<td scope="row"><?php _e('Date','unilevel-mlm-pro');?></td>
					<td><?php _e('Amount','unilevel-mlm-pro');?></td>
       <td><?php _e('Action','unilevel-mlm-pro');?></td>
				  </tr>
<?php $detailsArr =  my_payout_function();
//_e("<pre>");print_r($detailsArr); exit; 
//$page_id = get_post_id('mlm_my_payout_details_page');
if(count($detailsArr)>0){
$mlm_settings = get_option('wp_mlm_general_settings');
	?>
			<?php foreach($detailsArr as $row) :  
					
		$amount = $row->commission_amount + $row->bonus_amount;

		?>
		<tr>
			<td><?= $row->payoutDate ?></td>
			<td><?= $mlm_settings['currency'].' '.$amount ?></td>
			<td><a href="<?=get_post_id_or_postname_for_payout('mlm_my_payout_details_page', $row->payout_id)?>">View</a></td>
			
		</tr>
		
		<?php endforeach; ?>
	<?php 
	}else{

	?>
	<div class="no-payout"><?php _e('You have not earned any commisssions yet.','unilevel-mlm-pro');?> </div>
	
	<?php 
	}
	?>
				</table>
			</td>
			<td width="40%">
				<table width="100%" border="0" cellspacing="10" cellpadding="1">
				  <tr>
					<td><strong><?php _e('Network Details','unilevel-mlm-pro');?></strong></td>
				  </tr>
				  
				  

				 	<tr>
					<td>
						<table width="100%" border="0" cellspacing="10" cellpadding="1">
							<tr>
								<td colspan="2"><strong><?php _e('Personal Sales','unilevel-mlm-pro');?></strong></td>
							</tr>
							
							<tr>
								<td><?php _e('My Personal Sales','unilevel-mlm-pro');?>: <?= $personalSales?></td>
			<td><?php _e('Active','unilevel-mlm-pro');?>: <?= $activePersonalSales?></td>
							</tr>
							<?php
		foreach($fivePersonalUsers as $key => $value)
		{
			_e("<tr>");
			foreach($value as $k=>$val)
			{
				_e("<td>".$val."</td>");
			}
			_e("</tr>");
		}
		?>
							<tr>
		<td colspan="2"><a href="?page_id=<?= $view_memberpage_id?>&lvl=1" style="text-decoration: none"><?php _e('View All','unilevel-mlm-pro');?></a></td>
		</tr>
							
						</table>
					</td>
				  </tr> 
				  
				</table>
			</td>
		  </tr>
		</table>

<?php

}
?>