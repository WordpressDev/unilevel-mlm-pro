<?php 
function mlm_withdrawal_process(){
	global $table_prefix;
	global $wpdb;
?>
<div class='wrap'>
	<div id="icon-users" class="icon32"></div><h1><?php _e('Process Individual User Withdrawal','unilevel-mlm-pro');?></h1><br />
	<div class="notibar msginfo" style="margin:10px;">
		<a class="close"></a>
		<p><?php _e('Use the form below to process an individual user withdrawal.','unilevel-mlm-pro');?></p>
		<p><strong><?php _e('Cash','unilevel-mlm-pro');?></strong> - <?php _e('Simply records a cash payment against the withdrawal with no further details.','unilevel-mlm-pro');?></p>
		<p><strong><?php _e('Cheque','unilevel-mlm-pro');?></strong> - <?php _e('Specify the Cheque Number, Cheque Date and Bank Name.','unilevel-mlm-pro');?></p>
		<p><strong><?php _e('Bank Transfer','unilevel-mlm-pro');?></strong> - <?php _e('Specify the Beneficiary Name, Account Number, Bank Name and Bank Transfer Code (optional).','unilevel-mlm-pro');?></p>
		<p><strong><?php _e('Other','unilevel-mlm-pro');?></strong> - <?php _e('For any other mode of payment. Just specify the payment details in the input box provided.','unilevel-mlm-pro');?></p>
	</div>	
</div>

<?php	
	if(isset($_POST['member_name'])){ 
	$id= $_POST['id'];
	$mname= $_POST['member_name'];
	$mid= $_POST['member_id'];
	$withdrawalMode= $_POST['withdrawalMode']; 
	$memail= $_POST['member_email'];
	$wamount= $_POST['withdrawal_amount'];
	
	if(isset($_POST['paydone'])){ 
	$id= $_POST['id']; 
	$user_id= $_POST['user_id'];
	$amount = empty($_POST['withdrawal_amount'])?'':$_POST['withdrawal_amount'];
	
	$cheque_no= empty($_POST['cheque_no'])?'':$_POST['cheque_no'];
	$cheque_date= empty($_POST['cheque_date'])?'':$_POST['cheque_date']; 
	$bank_name= empty($_POST['cbank_name'])?'':$_POST['cbank_name']; 

	$user_bank_name= empty($_POST['btbank_name'])?'':$_POST['btbank_name']; 
	$user_bank_account_no= empty($_POST['btaccount_no'])?'':$_POST['btaccount_no']; 
	$banktransfer_code= empty($_POST['bt_code'])?'':$_POST['bt_code']; 
	$beneficiary= empty($_POST['bt_benificiary'])?'':$_POST['bt_benificiary']; 
	$other_comments= empty($_POST['other_comments'])?'':$_POST['other_comments']; 
	$comment= empty($_POST['specified'])?'':$_POST['specified']; 
	
	$sql = "UPDATE {$table_prefix}mlm_withdrawal SET `cheque_no`='".$cheque_no."',
		`cheque_date`='".$cheque_date."',`bank_name`='".$bank_name."',`banktransfer_code`='".$banktransfer_code."', 
		`user_bank_name`='".$user_bank_name."',`user_bank_account_no`='".$user_bank_account_no."',
		`beneficiary`='".$beneficiary."',`payment_processed`='1',`payment_processed_date`= NOW(), 
		`other_comments`='".$other_comments."' WHERE `id`= '".$id."' AND `user_id`= '".$user_id."'";
	$res= $wpdb->query($sql);
	$closing_bal = $wpdb->get_var("SELECT closing_bal FROM {$table_prefix}mlm_transaction  WHERE id = (select max(id) from {$table_prefix}mlm_transaction where user_id='".$user_id."')");
	$opening_bal = $closing_bal;
        $closing_bal = $opening_bal - $amount;
	$wpdb->query("INSERT INTO {$table_prefix}mlm_transaction  set 
                                user_id ='".$user_id."', 
                                dr_id = '".$id."',
                                opening_bal = '".$opening_bal."',
                                dr_amount = '".$amount."', 
                                closing_bal = '".$closing_bal."',    
                                transaction_date='".date('Y-m-d H:i:s')."',
                                transaction_type='2',
                                comment='Net Withdrawal Amount Mode: {$withdrawalMode}'");
        
		if($res=='1') {  
			//wp_redirect(get_option('siteurl').'/wp-admin/admin.php?page=admin-mlm-pending-withdrawal'); ?>
		
			<script>window.location.href="<?= site_url().'/wp-admin/admin.php?page=admin-mlm-pending-withdrawal'?>"</script>
		<?php }
	}
}
?>
<div class='wrap'>
<form method="POST" action="" id="paydone_form" name="payment_complete">
	<table border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td><?php _e('Member Id','unilevel-mlm-pro')?></td>
			<td><input type="text" name="member_id" id="mid" size="40" value="<?php if(!empty($mid)) _e($mid); ?>" readonly></td>
		</tr>
		<tr>
			<td><?php _e('Member User Name','unilevel-mlm-pro')?></td>
			<td><input type="text" name="member_name" id="mname" size="40" value="<?php if(!empty($mname)) _e($mname); ?>" readonly></td>
		</tr>
		<tr>
			<td><?php _e('Withdrawal Mode','unilevel-mlm-pro')?></td>
			<td><input type="text" name="withdrawalMode" id="mwid" size="40" value="<?php if(!empty($withdrawalMode)) _e($withdrawalMode); ?>" readonly></td>
		</tr>
		<tr>
			<td><?php _e('Member Email','unilevel-mlm-pro')?></td>
			<td><input type="text" name="member_email" id="memail" size="40" value="<?php if(!empty($memail)) _e($memail); ?>" readonly></td>
		</tr>
		<tr>
			<td><?php _e('Amount','unilevel-mlm-pro')?></td>
			<td><input type="text" name="withdrawal_amount" id="wamount" size="40" value="<?php if(!empty($wamount)) _e($wamount); ?>" readonly></td>
		</tr>
		<tr>
			<td><?php _e('Details/Comments','unilevel-mlm-pro')?></td>
			<td>

				<textarea  name="other_comments"   onblur="return allowspace(this.value,'other_comments');" id="other_comments" placeholder="" style="margin: 1px; width: 236px; height: 68px;"></textarea>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type='hidden' name='id' value='<?php _e($id); ?>'>
				<input type='hidden' name='user_id' value='<?php  _e($mid); ?>'>
				<input type="submit" name="paydone" id="paydone" value="<?php _e('Process','unilevel-mlm-pro')?>">
			</td>
		</tr>
	</table>
</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$("input[name='pmode']").click(function() {
			var method = $(this).val();
			$("div.ptype").hide();
			$("#mode-" + method).show();
			if(method=='cheque'){
				$("#mode-" + method+" input" ).removeAttr('disabled');
				$("#mode-bank-transfer input" ).attr('disabled', 'disabled');
				$("#mode-other input" ).attr('disabled', 'disabled');
			}
			else if(method=='bank-transfer'){
				$("#mode-" + method+" input" ).removeAttr('disabled');
				$("#mode-cheque input" ).attr('disabled', 'disabled');
				$("#mode-other input" ).attr('disabled', 'disabled');
			}
			else if(method=='other'){
				$("#mode-" + method+" input" ).removeAttr('disabled');
				$("#mode-cheque input" ).attr('disabled', 'disabled');
				$("#mode-bank-transfer input" ).attr('disabled', 'disabled');
			}
			else{
				$("#mode-bank-transfer input" ).attr('disabled', 'disabled');
				$("#mode-cheque input" ).attr('disabled', 'disabled');
				$("#mode-other input" ).attr('disabled', 'disabled');
			}

		});
	});
</script>
<?php } ?>