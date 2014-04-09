<?php
function mlmRoyaltyBonus()
{

	//get database table prefix
	$table_prefix = mlm_core_get_table_prefix();
	
	$error = '';
	$chk = 'error';
	
	//get no. of level
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
	
	
	//most outer if condition
	if(isset($_POST['mlm_royalty_bonus_settings']))
	{

		$per_ref =  count(array_filter($_POST['per_ref']));
		$pay_value = count(array_filter($_POST['pay_value']));
		$level = $_POST['level'];
		
		if ( $per_ref==0 ) 
			$error .= "\n Please Specify No. of Personal Referral.";
		
		if ( $pay_value==0 ) 
			$error .= "\n Please Specify Payout Value.";

        if ( checkInputField($level) ) 
			$error .= "\n Please Select Level.";


		
		//if any error occoured
		if(!empty($error))
			$error = nl2br($error);
		else
		{
			$chk = '';
			
			update_option('wp_mlm_royalty_bonus_settings', $_POST);
			$url = get_bloginfo('url')."/wp-admin/admin.php?page=admin-settings&tab=deduction";
			_e("<script>window.location='$url'</script>");
			$msg = _e("<span style='color:green;'>Your bonus has been successfully updated.</span>",'unilevel-mlm-pro');
		}
	}// end outer if condition
	if($chk!='')
	{
		$mlm_settings = get_option('wp_mlm_royalty_bonus_settings');
?>
<div class='wrap1'>
	<h2><?php _e('Royalty Bonus Settings','unilevel-mlm-pro');?>  </h2>
	<div class="notibar msginfo">
		<a class="close"></a>
		<p><?php _e('Royalty Bonus is a % bonus paid to a member on the total commissions earned by a particular level under the member.','unilevel-mlm-pro');?></p>
<p><strong><?php _e('No. of Personal Referrers','unilevel-mlm-pro');?> - </strong><?php _e('This is the value of the personal referrers which would trigger a bonus distribution in the network.','unilevel-mlm-pro');?></p>
<p><strong><?php _e('Payout Value','unilevel-mlm-pro');?> -</strong> <?php _e('This is the payout value in terms of the percentage amount that would be paid to the member.','unilevel-mlm-pro');?></p>
<p><strong><?php _e('Level','unilevel-mlm-pro');?> -</strong> <?php _e(' The system would calculate the total commissions for Level X under the member and distribute a bonus equal to Y% (the payout value above) of the total commissions at Level X.','unilevel-mlm-pro');?></p>
<p><?php _e('eg. Slab 1 = Personal Referrals = 5, Payout Value = 2 and Level = Level 1, then the system would distribute a bonus equal to 2% of the total commission earned by all the members who are at Level of that member.','unilevel-mlm-pro');?></p>
<p><strong><?php _e('Option 1','unilevel-mlm-pro');?></strong></p>
<p><?php _e('If the first bonus slab is as above and the second bonus slab is as follows','unilevel-mlm-pro');?></p>
<p><?php _e('Slab 2 = Personal Referrals = 10, Payout Value = 4 and Level = Level 1.','unilevel-mlm-pro');?></p>
</br>
<p><?php _e('Then, if this member has already referred 10 or more people, he would stop earning the bonus for Slab 1 as he cannot earn both 2% and 4% bonus for the same level.','unilevel-mlm-pro');?></p>
<p><strong><?php _e('Option 2','unilevel-mlm-pro');?></strong></p>
<p><?php _e('The first and second bonus slabs are as above and the third bonus slab is as follows:','unilevel-mlm-pro');?></p>
<p><?php _e('Slab 3 = Personal Referrals = 20, Payout Value = 1, Level = Level 2.','unilevel-mlm-pro');?></p>
</br>
<p><?php _e('Then, if this member has already reached 20 or more personal referrals, he would earn the bonus for both Slab 2 and Slab 3. As in the above Option 1 he would stop earning the commission for Slab 1 as Slab 2 is a higher version of Slab 1.','unilevel-mlm-pro');?></p>

	</div>
	
	<?php if($error) :?>
	<div class="notibar msgerror">
		<a class="close"></a>
		<p> <strong><?php _e('Please Correct the following Error','unilevel-mlm-pro');?> :</strong> <?php _e($error); ?></p>
	</div>
	<?php endif; ?>
	<?php if(!empty($msg)) :?>
	<div class="notibar msgerror">
		<a class="close"></a>
		<p><?php  _e($msg); ?></p>
	</div>
	<?php endif; ?>
	

<?php
	if(empty($mlm_settings))
	{
?>
<form name="admin_bonus_settings" method="post" action="">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
		
		
		<tr>

			<td>
				<INPUT type="button" value="<?php _e('Add Row','unilevel-mlm-pro')?>" onclick="addRow('dataTable')" class='button-primary' />
    			<INPUT type="button" value="<?php _e('Delete Row','unilevel-mlm-pro')?>" onclick="deleteRow('dataTable')" class='button-primary' />
				<div class="toggle-visibility" id="admin-mlm-bonus-slab"><?php _e('Add or remove bonus slab.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<TABLE id="dataTableheading" cellspacing="5" cellpadding="5"  border="0" width="450">
		<TR>
			<TD align="left" width="10%"><strong><?php _e('Select','unilevel-mlm-pro');?></strong></TD>
			<TD align="left" width="40%"> <strong><?php _e('No. of Personal Referrals','unilevel-mlm-pro');?></strong></TD>

			<TD align="left" width="25%"> <strong><?php _e('Payout Value (%)','unilevel-mlm-pro');?></strong></TD>
			<TD align="left" width="25%"><strong><?php _e('Level','unilevel-mlm-pro');?></strong></TD>
		</TR>
	</TABLE>
	<br\>
	<TABLE id="dataTable"  cellspacing="5" cellpadding="5" border="0" width="450">
		<TR>
			<TD align="left" width="10%"><INPUT type="checkbox" name="chk[]"/></TD>
			<TD align="left" width="40%"> <INPUT type="text" name="per_ref[]" size="15" /> </TD>

			<TD align="center" width="25%"> <INPUT type="text" name="pay_value[]" size="10" /> </TD>
			<TD align="center" width="25%"> 
			<select name="level[]" id="level" >
				<option value=""><?php _e('Select Level','unilevel-mlm-pro');?></option> 
				<?php  for($k=1;$k<=$mlm_no_of_level;$k++) { ?>
				<option value="<?php echo $k; ?>" <?= (isset($_POST['level']) && $_POST['level']==$k) ? 'selected':''?>><?php _e('Level '.$k,'unilevel-mlm-pro');?></option>
				<?php } ?>
				</select> </TD>
		</TR>
	</TABLE>
	
	<table border="0" width="100%">	
		<tr>
			<td>
		<p class="submit">
	<input type="submit" name="mlm_royalty_bonus_settings" id="mlm_royalty_bonus_settings" value="<?php _e('Update Options', 'unilevel-mlm-pro');?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
	</p>
			</td>
		<tr>
	</table>
</form>
</div>
<script language="JavaScript">
  populateArrays();
</script>
<?php
		}
		else if(!empty($mlm_settings))
		{
			?>
		
			<form name="admin_bouns_settings" method="post" action="">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
		<tr>
			<td>
				<INPUT type="button" value="<?php _e('Add Row','unilevel-mlm-pro')?>" onclick="addRow('dataTable')" class='button-primary'/>
    			<INPUT type="button" value="<?php _e('Delete Row','unilevel-mlm-pro')?>" onclick="deleteRow('dataTable')" class='button-primary'/>
				<div class="toggle-visibility" id="admin-mlm-bonus-slab"><?php _e('Add or remove bonus slab.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<TABLE id="dataTableheading" cellspacing="5" cellpadding="5"  border="0" width="450" >
		<TR>
			<TD align="left" width="10%"><strong><?php _e('Select','unilevel-mlm-pro');?></strong></TD>
			<TD align="left" width="40%"> <strong><?php _e('No. of Personal Referrals','unilevel-mlm-pro');?></strong></TD>

			<TD align="left" width="25%"> <strong><?php _e('Payout Value (%)','unilevel-mlm-pro');?></strong></TD>
			<TD align="left" width="25%"><strong><?php _e('Level','unilevel-mlm-pro');?></strong></TD>
		</TR>
	</TABLE>
	<br\>
		<TABLE id="dataTable"   cellspacing="5" cellpadding="5" border="0" width="450">
		<?php
			$i = 0;
			while( $i<count($mlm_settings['per_ref']) )
			{
		?>
        	<TR>
           		<TD align="left" width="10%"><INPUT type="checkbox" name="chk[]"/></TD>
				<TD align="left" width="40%"> <INPUT type="text" name="per_ref[]" size="15" value="<?= $mlm_settings['per_ref'][$i]?>"/> </TD>
				<TD align="left" width="25%"> 
			 <INPUT type="text" name="pay_value[]" size="10" value="<?= $mlm_settings['pay_value'][$i]?>"/> </TD>
				<TD align="left" width="25%"> 
			<select name="level[]" id="level" >
				<option value=""><?php _e('Select Level','unilevel-mlm-pro');?></option> 
				<?php  for($k=1;$k<=$mlm_no_of_level;$k++) { ?>
				<option value="<?php echo $k; ?>" <?= $mlm_settings['level'][$i]==$k ? 'selected':''?>><?php _e('Level '.$k,'unilevel-mlm-pro');?></option>
				<?php } ?>
				</select> </TD>
				
				
        	</TR>    	
		<?php
				$i++;
			}
		?>
		</TABLE>
	
	<p class="submit">
	<input type="submit" name="mlm_royalty_bonus_settings" id="mlm_royalty_bonus_settings" value="<?php _e('Update Options', 'unilevel-mlm-pro');?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
	</p>
</form>
</div>
<script language="JavaScript">
  populateArrays();
</script>
			<?php
		}
		
	} // end if statement
	else
		 _e($msg);
} //end mlmBonus function
?>