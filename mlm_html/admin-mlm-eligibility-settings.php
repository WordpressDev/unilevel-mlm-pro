<?php
function mlmEligibility()
{
	//get database table prefix
	$table_prefix = mlm_core_get_table_prefix();
	
	$error = '';
	$chk = 'error';
	
	//most outer if condition
	if(isset($_POST['mlm_eligibility_settings']))
	{
		$personal_referrer = sanitize_text_field( $_POST['personal_referrer'] );
		
		
		if ( checkInputField($personal_referrer) ) 
			$error .= "\n Please specify your personal active referrers.";
		
		//if any error occoured
		if(!empty($error))
			$error = nl2br($error);
		else
		{
			$chk = '';
			update_option('wp_mlm_eligibility_settings', $_POST);
			$url = get_bloginfo('url')."/wp-admin/admin.php?page=admin-settings&tab=payout";
			_e("<script>window.location='$url'</script>");
			$msg = "<span style='color:green;'>Your eligibility settings has been successfully updated.</span>";
		}
	}// end outer if condition
	if($chk!='')
	{
		$mlm_settings = get_option('wp_mlm_eligibility_settings');
		?>
		
<div class='wrap1'>
	<h2><?php _e('Eligibility Settings','unilevel-mlm-pro');?> </h2>
	<div class="notibar msginfo">
		<a class="close"></a>
		<p><?php _e('These settings define the criteria for a member to start earning regular commissions and bonuses from his downline.','unilevel-mlm-pro');?></p>
		<p><strong><?php _e('No. of Personal Referrers','unilevel-mlm-pro');?> -</strong> <?php _e('The minimum number of people a member needs to sponsor into the network before he becomes entitled to earn commissions from his network.','unilevel-mlm-pro');?></p>
	</div>
	<?php if($error) :?>
	<div class="notibar msgerror">
		<a class="close"></a>
		<p> <strong><?php _e('Please Correct the following Error','unilevel-mlm-pro');?> :</strong> <?php _e($error); ?></p>
	</div>
	<?php endif; ?>
	

		
<?php
		if(empty($mlm_settings))
		{
?>
	
	<form name="admin_eligibility_settings" method="post" action="">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('mlm_personal_referrer');">
				<?php _e('No. of Personal Referrer(s)','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="text" name="personal_referrer" id="personal_referrer" size="10" value="<?php if(!empty($_POST['personal_referrer'])) _e(htmlentities($_POST['personal_referrer']));?>">
				<div class="toggle-visibility" id="mlm_personal_referrer"><?php _e('Please specify personal referrer by you.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		</table>
		<p class="submit">
	<input type="submit" name="mlm_eligibility_settings" id="mlm_eligibility_settings" value="<?php _e('Update Options', 'unilevel-mlm-pro');?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
	</p>
</form>


<?php
		}
		else if(!empty($mlm_settings))
		{
		
			?>
		
			<form name="admin_eligibility_settings" method="post" action="">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('mlm_personal_referrer');">
				<?php _e('No. of Personal Referrer(s)','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="text" name="personal_referrer" id="personal_referrer" size="10" value="<?php if($mlm_settings['personal_referrer']!='') _e($mlm_settings['personal_referrer']);?>">
				<div class="toggle-visibility" id="mlm_personal_referrer"><?php _e('Please specify personal referrer by you.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		</table>
		<p class="submit">
	<input type="submit" name="mlm_eligibility_settings" id="mlm_eligibility_settings" value="<?php _e('Update Options', 'unilevel-mlm-pro');?> &raquo;" class='button-primary' onclick="needToConfirm = false;" >
	</p>
</form>

<script language="JavaScript">
  populateArrays();
</script>
<?php
		}
		
	?>
	</div>
	<?php 	
	} // end if statement
	else
		 _e($msg);

} //end mlmEligibility funtion
?>