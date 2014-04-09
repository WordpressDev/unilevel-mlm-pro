<?php
function mlmRegularBonus()
{

	//get database table prefix
	$table_prefix = mlm_core_get_table_prefix();
	
	$error = '';
	$chk = 'error';
	
	//get no. of level
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
	
	
	//most outer if condition
	if(isset($_POST['mlm_regular_bonus_settings']))
	{

		$per_ref =  count(array_filter($_POST['per_ref']));
	

		$pay_value = count(array_filter($_POST['pay_value']));

		
		if ( $per_ref==0 ) 
			$error .= "\n Please Specify No. of Personal Referral.";
					
		if ( $pay_value==0 ) 
			$error .= "\n Please Specify Payout Value.";


		
		//if any error occoured
		if(!empty($error))
			$error = nl2br($error);
		else
		{
			$chk = '';
			
			update_option('wp_mlm_regular_bonus_settings', $_POST);
			$url = get_bloginfo('url')."/wp-admin/admin.php?page=admin-settings&tab=royalty_bonus";
			_e("<script>window.location='$url'</script>");
			$msg = _e("<span style='color:green;'>Your bonus has been successfully updated.</span>",'unilevel-mlm-pro');
		}
	}// end outer if condition
	if($chk!='')
	{
		$mlm_settings = get_option('wp_mlm_regular_bonus_settings');
?>
<div class='wrap1'>
	<h2><?php _e('Regular Bonus Settings','unilevel-mlm-pro');?>  </h2>
	<div class="notibar msginfo">
		<a class="close"></a>
		<p><?php _e('Regular bonus is a one time payment made to a member in the network based on the number of personal referrers. To add a new bonus slab click the Add Row Button. To delete a particular bonus slab, select the checkbox against the slab and click the Delete Row Button.','unilevel-mlm-pro');?></p>
<p><strong><?php _e('No. of Personal Referrers','unilevel-mlm-pro');?> - </strong><?php _e('This is the value of the personal referrers  which would trigger a bonus distribution in the network. If the first slab has a value of 5 personal referrers while the next slab has 10 personal referrers, this implies that the member has to sponsor 5 members to get the bonus of Slab1 and a total of 15 members (10 more members) in the network in order to get the bonus for the
Slab2.','unilevel-mlm-pro');?></p>
<p><strong><?php _e('Payout Value','unilevel-mlm-pro');?> -</strong> <?php _e('This is the fixed payout   amount paid to the member on achieving the milestone.','unilevel-mlm-pro');?></p>

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
			<TD align="left" width="12%"><strong><?php _e('Select','unilevel-mlm-pro');?></strong></TD>
			<TD align="left" width="44%"> <strong><?php _e('No. of Personal Referrals','unilevel-mlm-pro');?></strong></TD>
			
			<TD align="left" width="44%"> <strong><?php _e('Payout Value','unilevel-mlm-pro');?></strong></TD>
		</TR>
	</TABLE>
	<br\>
	<TABLE id="dataTable"  cellspacing="0" cellpadding="0" border="0" width="450">
		<TR>
			<TD align="left" width="12%"><INPUT type="checkbox" name="chk[]"/></TD>
			<TD align="left" width="44%"> <INPUT type="text" name="per_ref[]" size="15" /> </TD>
			
			<TD align="left" width="44%"> <INPUT type="text" name="pay_value[]" size="15" /> </TD>
			
		</TR>
	</TABLE>
	
	<table border="0" width="100%">	
		<tr>
			<td>
		<p class="submit">
	<input type="submit" name="mlm_regular_bonus_settings" id="mlm_regular_bonus_settings" value="<?php _e('Update Options', 'unilevel-mlm-pro');?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
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
			<TD align="left" width="12%"><strong><?php _e('Select','unilevel-mlm-pro');?></strong></TD>
			<TD align="left" width="44%"> <strong><?php _e('No. of Personal Referrals','unilevel-mlm-pro');?></strong></TD>
			<TD align="left" width="44%"> <strong><?php _e('Payout Value','unilevel-mlm-pro');?></strong></TD>
			
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
           		<TD align="left" width="12%"><INPUT type="checkbox" name="chk[]"/></TD>
				<TD align="left" width="44%"> <INPUT type="text" name="per_ref[]" size="15" value="<?= $mlm_settings['per_ref'][$i]?>"/> </TD>
				
				<TD align="left" width="44%"> <INPUT type="text" name="pay_value[]" size="15" value="<?= $mlm_settings['pay_value'][$i]?>"/> </TD>
				
				
				
        	</TR>    	
		<?php
				$i++;
			}
		?>
		</TABLE>
	
	<p class="submit">
	<input type="submit" name="mlm_regular_bonus_settings" id="mlm_regular_bonus_settings" value="<?php _e('Update Options', 'unilevel-mlm-pro');?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
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