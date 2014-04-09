<?php
function mlmPayout()
{
	//get database table prefix
	$table_prefix = mlm_core_get_table_prefix();
	
	$error = '';
	$chk = 'error';
	
	//get no. of level
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
	$mlm_product_value=$mlm_general_settings['single-sale'];
	//most outer if condition 
	if(isset($_POST['mlm_payout_settings']))
	{
//echo "<pre>"; print_r($_POST); 
		$company_commission = sanitize_text_field( $_POST['company_commission_amount'] );
		$referral_commission = sanitize_text_field( $_POST['referral_commission_amount'] );
		
			
		if ( checkInputField($company_commission) ) 
			$error .= "\n Please specify your company commission.";
		
		if ( checkInputField($referral_commission) ) 
			$error .= "\n Please specify your referral commission.";
		
        for($k=1;$k<=$mlm_no_of_level;$k++) {
	
		if ( checkInputField(sanitize_text_field($_POST['level'.$k.'_commission']))) 
		{
			 $error .= "\n Please specify your Level $k Commission.";
        }

         }

		
		//if any error occoured
		if(!empty($error))
			$error = nl2br($error);
		else
		{
			$chk = '';
			update_option('wp_mlm_payout_settings', $_POST);
			$url = get_bloginfo('url')."/wp-admin/admin.php?page=admin-settings&tab=regular_bonus";
			_e("<script>window.location='$url'</script>");
			$msg = "<span style='color:green;'>Your payout settings has been successfully updated.</span>";
		}
	}// end outer if condition
	if($chk!='')
	{
		$mlm_settings = get_option('wp_mlm_payout_settings');
		?>


<div class='wrap1'>
	<h2><?php _e('Payout Settings','unilevel-mlm-pro');?>  </h2>
	<div class="notibar msginfo">
		<a class="close"></a>
		<p><?php _e('These settings define the commissions that would be distributed in the network for a single sale in the network.','unilevel-mlm-pro');?></p>
		<p><strong><?php _e('Company Share','unilevel-mlm-pro');?> - </strong><?php _e('This is a fixed amount that is payable to the company (First User in the network) for every sale in the network.','unilevel-mlm-pro');?></p>
		<p><strong><?php _e('Referral Commission','unilevel-mlm-pro');?></strong> - <?php _e('This is a referral commission that is paid to the sponsor for sponsoring a new member to the network.  This is over and above his regular Level based commissions. You can set this to zero in case you do not want to distribute any referral commission as typically this would be covered
under Level 1 Commissions.','unilevel-mlm-pro');?></p>
		<p><strong><?php _e('Level Commissions','unilevel-mlm-pro');?> - </strong><?php _e('These are the amounts payable at various levels in the upline depending on the No. of Levels setting defined under the General Tab.','unilevel-mlm-pro');?></p>
<p><strong><?php _e('Note :','unilevel-mlm-pro');?>  </strong><?php _e('The sum of all the commissions mentioned above should always be less than or equal to the
Product Value as defined in the General Tab.','unilevel-mlm-pro');?></p>		
	</div>
	<?php if($error) :?>
	<div class="notibar msgerror">
		<a class="close"></a>
		<p> <strong><?php _e('Please Correct the following Error','unilevel-mlm-pro');?> :</strong> <?php _e($error); ?></p>
	</div>
	<?php endif; ?>
	<script>
	 function check_total_amount(total,num) 
{ 
var direct_cpy=parseFloat(document.getElementById('company_commission_amount').value);
var direct_reff=parseFloat(document.getElementById('referral_commission_amount').value);
var level_comm=0;
for(var i=1;i<=num;i++)
{
var x='level'+i+'_commission';
level_comm += parseFloat(document.getElementById(x).value);

}
var result=direct_cpy+direct_reff+level_comm;

if(result>total)
 { 
alert('Commissions should always be less than or equal to the Product Value.Please Correct this.');
return false;
}

}
</script>
<?php
	if(empty($mlm_settings))
	{
?>
		<form name="admin_payout_settings" method="post" action="" onsubmit="return check_total_amount('<?php echo $mlm_product_value; ?>','<?php echo $mlm_no_of_level; ?>');">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
		
		<tr>
	<th scope="row" class="admin-settings">
				<a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('admin-mlm-company_commission_amount');">
				<?php _e('Company Share','unilevel-mlm-pro');?> :</a>
			</th>
			<td>
			<input type="text" name="company_commission_amount" id="company_commission_amount" size="10" value="<?php if(!empty($_POST['company_commission_amount'])) _e(htmlentities($_POST['company_commission_amount']));?>">
				<div class="toggle-visibility" id="admin-mlm-company_commission_amount"><?php _e('Please specify Company commission.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		<tr>
	<th scope="row" class="admin-settings">
				<a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('admin-mlm-referral_commission_amount');">
				<?php _e('Referral Commission','unilevel-mlm-pro');?> :</a>
			</th>
			<td>
			<input type="text" name="referral_commission_amount" id="referral_commission_amount" size="10" value="<?php if(!empty($_POST['referral_commission_amount'])) _e(htmlentities($_POST['referral_commission_amount']));?>">
				<div class="toggle-visibility" id="admin-mlm-referral_commission_amount"><?php _e('Please specify referral commission.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
    
<?php    for($j=1;$j<=$mlm_no_of_level;$j++) {           ?>		  

<th scope="row" class="admin-settings">
				<a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('admin-mlm-level<?php echo $j; ?>-commission');">
				<?php _e('Level '.$j.' Commission','unilevel-mlm-pro');?> :</a>
			</th>
			<td>
			<input type="text" name="level<?php echo $j; ?>_commission" id="level<?php echo $j; ?>_commission" size="10" value="<?php if(!empty($_POST['level'.$j.'_commission'])) _e(htmlentities($_POST['level'.$j.'_commission']));?>">
				<div class="toggle-visibility" id="admin-mlm-level<?php echo $j; ?>-commission"><?php _e('Please specify Level '.$j.' Commission.','unilevel-mlm-pro');?></div>
			</td>
		</tr>


<?php  }  ?>

	
		</table>
		<p class="submit">
	<input type="submit" name="mlm_payout_settings" id="mlm_payout_settings" value="<?php _e('Update Options', 'unilevel-mlm-pro');?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
	</p>
	</form>

	<?php
		}
		else if(!empty($mlm_settings))
		{
			?>
			<form name="admin_payout_settings" method="post" action="" onsubmit="return check_total_amount('<?php echo $mlm_product_value; ?>','<?php echo $mlm_no_of_level; ?>');">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
		
		
		<tr>
	<th scope="row" class="admin-settings">
				<a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('admin-mlm-company_commission_amount');">
				<?php _e('Company Share','unilevel-mlm-pro');?> :</a>
			</th>
			<td>
			<input type="text" name="company_commission_amount" id="company_commission_amount" size="10" value="<?php if(!empty($mlm_settings['company_commission_amount'])) _e($mlm_settings['company_commission_amount'],'unilevel-mlm-pro');?>">
				<div class="toggle-visibility" id="admin-mlm-company_commission_amount"><?php _e('Please specify Company commission.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		<tr>
	<th scope="row" class="admin-settings">
				<a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('admin-mlm-referral_commission_amount');">
				<?php _e('Referral Commission','unilevel-mlm-pro');?> :</a>
			</th>
			<td>
			<input type="text" name="referral_commission_amount" id="referral_commission_amount" size="10" value="<?php if(!empty($mlm_settings['referral_commission_amount'])) _e($mlm_settings['referral_commission_amount'],'unilevel-mlm-pro');?>">
				<div class="toggle-visibility" id="admin-mlm-referral_commission_amount"><?php _e('Please specify referral commission.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
<?php    for($j=1;$j<=$mlm_no_of_level;$j++) {           ?>		  

<th scope="row" class="admin-settings">
				<a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('admin-mlm-level<?php echo $j; ?>-commission');">
				<?php _e('Level '.$j.' Commission','unilevel-mlm-pro');?> :</a>
			</th>
			<td>
			<input type="text" name="level<?php echo $j; ?>_commission" id="level<?php echo $j; ?>_commission" size="10" value="<?php if(!empty($mlm_settings['level'.$j.'_commission'])) _e($mlm_settings['level'.$j.'_commission']);?>">
				<div class="toggle-visibility" id="admin-mlm-level<?php echo $j; ?>-commission"><?php _e('Please specify Level '.$j.' Commission.','unilevel-mlm-pro');?></div>
			</td>
		</tr>


<?php  }  ?>
		  
		</table>
		<p class="submit">
	<input type="submit" name="mlm_payout_settings" id="mlm_payout_settings" value="<?php _e('Update Options', 'unilevel-mlm-pro');?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
	</p>
	</form>

	<script language="JavaScript">
  populateArrays();
</script>
<?php
		}
		
	
	} // end if statement
	else
		 _e($msg);
		
		
} //end mlmPayout function
?>
