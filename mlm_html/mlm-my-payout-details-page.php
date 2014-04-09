<?php 
function mlm_my_payout_details_page($id=''){
global $table_prefix;
global $wpdb;
global $date_format;
$url = plugins_url();
	
if($id == '')
	$detailArr = my_payout_details_function();
else 
	$detailArr = my_payout_details_function($id);



	if(count($detailArr)>0)
	{

	    $memberId = $detailArr['memberId']; 
		$payoutId = $detailArr['payoutId'];
		$comissionArr = getCommissionByPayoutId($memberId,$payoutId );
		$RegularbonusArr = getRegularBonusByPayoutId($memberId,$payoutId );
		$RoyaltybonusArr = getRoyaltyBonusByPayoutId($memberId,$payoutId );
		$mlm_settings = get_option('wp_mlm_general_settings');
		$comm_ssion=array('','Level','Refferral','Company','Left over');			
		?>
		<!--<script src="initiate.js" type="text/javascript"></script>-->
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
					<td scope="row"><?php _e('Name','unilevel-mlm-pro');?></td>
					<td><?=$detailArr['name'] ?></td>
				  </tr>
				  <tr>
					<td scope="row"><?php _e('ID','unilevel-mlm-pro');?></td>
					<td><?=$detailArr['userKey'] ?></td>
				  </tr>
				  <tr>
					<td scope="row"><?php _e('Payout ID','unilevel-mlm-pro');?></td>
					<td><?=$detailArr['payoutId'] ?></td>
				  </tr>
				  <tr>
					<td scope="row"><?php _e('Date','unilevel-mlm-pro');?></td>
					<td><?=$detailArr['payoutDate'] ?></td>
				  </tr>
				</table>
			</td>
			<td width="40%">
				<table width="100%" border="0" cellspacing="10" cellpadding="1">
				  <tr>
					<td><strong><?php _e('Payout Details','unilevel-mlm-pro');?></strong></td>
				  </tr>
				   <tr>
					<td>
						<table width="100%" border="0" cellspacing="10" cellpadding="1">
							<tr>
								<td colspan="3"><strong><?php _e('Commission','unilevel-mlm-pro');?></strong></td>
							</tr>
							
							<tr>
								<td><?php _e('User Name','unilevel-mlm-pro');?></td>
								<td><?php _e('Commission Type','unilevel-mlm-pro');?></td>
								<td><?php _e('Amount','unilevel-mlm-pro');?></td>
							</tr>
							<?php foreach($comissionArr as $comm ) :?>
							
							<tr>
								<td><?= getusernamebykey($comm['child_ids']) ?></td>
								<td><?= $comm_ssion[$comm['comm_type']] ?></td>
								<td><?= $mlm_settings['currency'].' '.$comm['amount'] ?></td>
							</tr>
							
							<?php endforeach; ?>
							
							
						</table>
                        
					</td>
				  </tr>
				   <?php if(count($RegularbonusArr)>0) : ?>
				   <tr>
					<td>
						<table width="100%" border="0" cellspacing="10" cellpadding="1">
							<tr>
								<td colspan="2"><strong><?php _e('Regular Bonus','unilevel-mlm-pro');?></strong></td>
							</tr>
							<?php foreach($RegularbonusArr as $bonus ) :?>
							<tr>
								<td><?= $bonus['bonusDate'] ?> </td>
								<td><?= $mlm_settings['currency'].' '.$bonus['amount'] ?></td>
							</tr>
							<?php endforeach; ?>
							
						</table>
					</td>
				  </tr>
				  <?php endif;?>
				  <?php if(count($RoyaltybonusArr)>0) : ?>
				   <tr>
					<td>
						<table width="100%" border="0" cellspacing="10" cellpadding="1">
							<tr>
								<td colspan="3"><strong><?php _e('Royalty Bonus','unilevel-mlm-pro');?></strong></td>
							</tr>
							<?php foreach($RoyaltybonusArr as $bonus ) :?>
							<tr>
								<td><?= $bonus['bonusDate'] ?> </td>
								<td><?= 'Level '.$bonus['level'] ?> </td>
								<td><?= $mlm_settings['currency'].' '.$bonus['amount'] ?></td>
							</tr>
							<?php endforeach; ?>
							
						</table>
					</td>
				  </tr>
				  <?php endif;?>
				</table>
			</td>
		  </tr>
		</table>
		
		
		<table width="100%" border="0" cellspacing="10" cellpadding="1" class="payout-summary">
			<tr>
				<td colspan="2"><strong><?php _e('Payout Summary','unilevel-mlm-pro');?></strong></td>
			</tr>
			<tr>
				<td width="50%"><?php _e('Commission Amount','unilevel-mlm-pro');?></td>
				<td width="50%" class="right"><?= $mlm_settings['currency'].' '.$detailArr['commamount'] ?></td>
			</tr>
            
			<tr>
				<td width="50%"><?php _e('Bonus Amount','unilevel-mlm-pro');?></td>
				<td width="50%" class="right" ><?= $mlm_settings['currency'].' '.$detailArr['bonusamount'] ?></td>
			</tr>
			<tr>
				<td width="50%"><?php _e('Sub-Total','unilevel-mlm-pro');?></td>
				<td width="50%" class="right"><?=$mlm_settings['currency'].' '.$detailArr['subtotal'] ?></td>
			</tr>
			
			<tr>
				<td width="50%"><strong><?php _e('Net Amount','unilevel-mlm-pro');?><?php if(!empty($cap)) _e($cap); ?></strong>	</td>
				<td width="50%" class="right"><strong><?=$mlm_settings['currency'].' '.$detailArr['netamount'] ?></strong></td>
			</tr>
			<tr>
				<td colspan="2" class="right">
				
				
				
				</td>
			</tr>
			<div class='show-payment-detail' style="display:none;">
			<tr class='show-payment-detail' style="display:none;"><td colspan='2'><?php _e(paymentDeatil($memberId,$payoutId));?></td></tr></div>
		</table>
		
		<script type="text/javascript">
			jQuery(document).ready(function ($){
				$(".view-payment").click( function(){
					$(".show-payment-detail").toggle();
				});
			});

			$(function(){
				$(".button").click(function() {
					var name = $("input#name").val();
					var memberid=$('#memberid').val();
					var payoutid=$('#payoutid').val();
					var dataString = 'name='+ name + '&wint_id=' + memberid + '&pay_id=' + payoutid;

					$.ajax({
						type: "POST",
						url: "<?php _e($url); ?>/unilevel-mlm-pro/mlm_html/delete_withdrawal.php",
						data: dataString,
						success: function() {
							$('#comment_form').html("<div id='message'></div>");
							$('#message').html("<h3 class='initiatedmsg'>Your Withdrawal Request Initiated.</h3>")
							.hide()
							.fadeIn(1500, function() {
								$('#message').append("<?php __('Thanks for Patience.','unilevel-mlm-pro')?>");
							});
						}
					});
					return false;
				});
			});
		</script>
			
		<?php
	}else{
	
		_e("<div class='notfound'>It Seems some error. Please contact adminisistrator ".get_option('admin_email')." for this issue.</div>");
	
	}	
	

	
}

function my_payout_details_function($id='')
{
	if ( is_user_logged_in() && isset($_REQUEST['pid']))
	{
	
		global $table_prefix;
		global $wpdb;
		global $current_user;
    	get_currentuserinfo();
		
if($id == '')		
		$userId = $current_user->ID;
else 
		$userId = $id; 	
		
		$sql = "SELECT {$table_prefix}mlm_users.id AS id , {$table_prefix}mlm_users.user_key FROM {$table_prefix}users,{$table_prefix}mlm_users WHERE {$table_prefix}mlm_users.username = {$table_prefix}users.user_login AND {$table_prefix}users.ID = '".$userId."'"; 
		$res = $wpdb->get_results($sql, ARRAY_A); 
		if(!empty($res)){
		$mlm_user_id = $res[0]['id'];
		$mlm_user_key = $res[0]['user_key']; }
		else{
		$mlm_user_id = '';
		$mlm_user_key = ''; 
		}
	
		
		 $sql = 	"SELECT 
					id, user_id, DATE_FORMAT(`date`,'%d %b %Y') as payoutDate, payout_id, commission_amount,bonus_amount,total_amt
                                        FROM 
					{$table_prefix}mlm_payout 
                                        WHERE 
					payout_id = '".$_REQUEST['pid']."' AND 
					user_id = '".$mlm_user_id."'";
		
		 $rs = mysql_query($sql);
		 $payoutDetail = array(); 
		 
		 if(mysql_num_rows($rs)>0)
		 {
		 	$row = mysql_fetch_array($rs);
	
			$payoutDetail['memberId'] = $mlm_user_id;
			$payoutDetail['name'] = $current_user->user_firstname.' '.$current_user->user_lastname; 
			$payoutDetail['userKey'] = $mlm_user_key;
			$payoutDetail['payoutId'] = $_REQUEST['pid']; 
			$payoutDetail['payoutDate'] = $row['payoutDate'];
			$payoutDetail['commamount'] = $row['commission_amount'];
			$payoutDetail['bonusamount'] = $row['bonus_amount'];
			$payoutDetail['subtotal'] = $row['total_amt'] ;
			$payoutDetail['netamount'] = number_format($payoutDetail['subtotal'], 2); 
		}
		 
		return $payoutDetail;	 
		 
	}	else{
	
		return null;
	}

}

function getCommissionByPayoutId($memberId,$payoutId )
{
	global $table_prefix;
	global $wpdb;
	if(isset($memberId) && isset($payoutId))
	{
	
		$memberId = $wpdb->get_var("
																												SELECT user_key 
																												FROM {$table_prefix}mlm_users 
																												WHERE id = '".$memberId."'");
		$sql = "SELECT 
					id, date_notified, parent_id, child_ids, amount, payout_id, comm_type 
				FROM 
					{$table_prefix}mlm_commission 
				WHERE 
					parent_id = '".$memberId."' AND 
					payout_id = '".$payoutId."' 
				";
				
		$myrows = $wpdb->get_results($sql, ARRAY_A );
			
		return $myrows;
	
	}else
	return null;

}

function getDirectReferralCommissionByPayoutId($memberId,$payoutId )
{
	global $table_prefix; 
	global $wpdb;
	if(isset($memberId) && isset($payoutId))
	{
	 	$sql = "SELECT 
					*
				FROM 
					{$table_prefix}mlm_referral_commission LEFT JOIN  {$table_prefix}mlm_users ON {$table_prefix}mlm_referral_commission.child_id = {$table_prefix}mlm_users.id
				WHERE 
					sponsor_id = '".$memberId."' AND 
					payout_id = '".$payoutId."' 
				";
				
		$myrows = $wpdb->get_results($sql, ARRAY_A );
			
		return $myrows;
	
	}else
	return null;

}

function getRegularBonusByPayoutId($memberId,$payoutId )
{
	global $table_prefix; 
	global $wpdb;
	if(isset($memberId) && isset($payoutId))
	{
		$sql = "SELECT 
					 id, DATE_FORMAT(`date_notified`,'%d %b %Y') as bonusDate, mlm_user_id, amount, payout_id ,bonus_type,level
				FROM 
					{$table_prefix}mlm_bonus 
				WHERE 
					mlm_user_id = '".$memberId."' AND 
					payout_id = '".$payoutId."' AND 
					bonus_type = '1' 
				";
				
		$myrows = $wpdb->get_results($sql, ARRAY_A );
			
		return $myrows;
	
	}else
	return null;

}
function getRoyaltyBonusByPayoutId($memberId,$payoutId )
{
	global $table_prefix; 
	global $wpdb;
	if(isset($memberId) && isset($payoutId))
	{
		$sql = "SELECT 
					 id, DATE_FORMAT(`date_notified`,'%d %b %Y') as bonusDate, mlm_user_id, amount, payout_id ,bonus_type,level
				FROM 
					{$table_prefix}mlm_bonus 
				WHERE 
					mlm_user_id = '".$memberId."' AND 
					payout_id = '".$payoutId."' AND 
					bonus_type = '2' 
				";
				
		$myrows = $wpdb->get_results($sql, ARRAY_A );
			
		return $myrows;
	
	}else
	return null;

}
function getWithDrawal($memberId,$payoutId){
	global $table_prefix;
	global $wpdb;
	
	if(isset($memberId) && isset($payoutId))
	{
		$sql = "SELECT 
					 withdrawal_initiated, payment_processed
				FROM 
					{$table_prefix}mlm_payout
				WHERE 
					user_id = '".$memberId."' AND 
					payout_id = '".$payoutId."' 
				";
				
		$myrows = $wpdb->get_results($sql, ARRAY_A );
			
		return $myrows;
	
	}else
	return null;

}

function paymentDeatil($memberId,$payoutId){
	global $table_prefix;
	global $wpdb;
	global $date_format;

	
	if(isset($memberId) && isset($payoutId))
	{
		$sql = "SELECT *
				FROM 
					{$table_prefix}mlm_payout
				WHERE 
					user_id = '".$memberId."' AND 
					payout_id = '".$payoutId."' 
				";
				
		$myrows = $wpdb->get_results($sql, ARRAY_A );
			
		$detail=$myrows[0];
		if($detail['payment_mode']=='cheque'){

			_e("<table><tr><th>Withdrawal Date</th><th>Payment Date</th><th>Payment Mode</th><th>Payment Detail</th></tr><tr><td>".$detail['withdrawal_initiated_date']."</td><td>".$detail['payment_processed_date']."</td><td>".$detail['payment_mode']."</td><td>Cheque No:&nbsp;".$detail['cheque_no']."<br/>Cheque Date: &nbsp;".$detail['cheque_date']."<br/>Bank Name:&nbsp;".$detail['bank_name']." <br/></td></tr></table>");
		}
		else
		if($detail['payment_mode']=='bank-transfer')
		{
			_e("<table><tr><th>Withdrawal Date</th><th>Payment Date</th><th>Payment Mode</th><th>Payment Detail</th></tr><tr><td>".$detail['withdrawal_initiated_date']."</td><td>".$detail['payment_processed_date']."</td><td>".$detail['payment_mode']."</td><td>Benificiary:&nbsp;".$detail['beneficiary']."<br/>Account No: &nbsp;".$detail['user_bank_account_no']."<br/>Banktransfer Code: &nbsp;".$detail['banktransfer_code']."<br/>Bank Name:&nbsp;".$detail['user_bank_name']." <br/></td></tr></table>");
		}
		else if($detail['payment_mode']=='cash')
		{
			_e("<table><tr><th>Withdrawal Date</th><th>Payment Date</th><th>Payment Mode</th></tr><tr><td>".$detail['withdrawal_initiated_date']."</td><td>".$detail['payment_processed_date']."</td><td>".$detail['payment_mode']."</td></tr></table>");
		}
		else
		if($detail['payment_mode']=='other')
		{
			_e("<table><tr><th>Withdrawal Date</th><th>Payment Date</th><th>Payment Mode</th><th>Payment Detail</th></tr><tr><td>".$detail['withdrawal_initiated_date']."</td><td>".$detail['payment_processed_date']."</td><td>".$detail['payment_mode']."</td><td>".$detail['other_comments']."</td></tr></table>");
		}
		
			
	
	}
	
}

?>
