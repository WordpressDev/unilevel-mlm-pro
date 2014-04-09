<?php

function mlm_epin_settings()
{
	global $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	if(isset($_POST['epin_save']))
	{	
		update_option('wp_mlm_epin_settings', $_POST);
		$epin_settings = get_option('wp_mlm_epin_settings');
		$mlm_settings = get_option('wp_mlm_general_settings');
		$epin_length=$mlm_settings['epin_length'];
		
		
		$range=$epin_settings['number_of_epins'];
		$epins_type=$epin_settings['epins_type'];
		for($i=0;$i<=$range-1;$i++)
		{
			$epin_no=epin_genarate($epin_length);
			//if generated key is already exist in the DB then again re-generate key
			do
			{
				$check = $wpdb->get_var("SELECT COUNT(*) ck 
														FROM {$table_prefix}mlm_epins 
														WHERE `epin_no` = '".$epin_no."'");
				$flag = 1;
				if($check == 1)
				{
					$epin_no = epin_genarate($epin_length);
					$flag = 0;
				}
			}while($flag==0);
			
				
			$query="insert into {$table_prefix}mlm_epins set epin_no='$epin_no',point_status='$epins_type', date_generated=now();";
			
			mysql_query($query)or die(mysql_error());
			if($i==$range-1)
			{
				$message=1;
			}
			else
			{
				$message=0;
			}
			
		}
	}
	else
	{	
		$epin_settings = get_option('wp_mlm_epin_settings');
	}
	
?>
<script>

function epinValidation()
{
	var number_of_epins=document.getElementById("number_of_epins").value;
	if(number_of_epins=="")
		{
			alert('<?php _e("The Number of ePins can not be empty. please Specify the ePins Number.","unilevel-mlm-pro")?>');
			document.getElementById('number_of_epins').focus();
			return false;
		}	
}	
</script>
<div class="wrap1">
<h2><?php _e("ePin Settings","unilevel-mlm-pro");?></h2>
<div class="notibar msginfo">
		<a class="close"></a>
		<p><?php _e('Use this tab to generate the ePins in your network which would be used for registering new members on the site. All ePins generated will be unique and the length shall be as specified in the General Tab.','unilevel-mlm-pro');?></p>
		<p><strong><?php _e('No. of ePins','unilevel-mlm-pro');?></strong> - <?php _e('Specify the number of ePins to generate.','unilevel-mlm-pro')?></p>
		<p><strong><?php _e('Type - Regular','unilevel-mlm-pro');?></strong> - <?php _e('A member registering with a Regular ePin shall be treated as a regular Paid member of the system. The commission for the member will be distributed amongst his upline and he will also earn commissions and bonuses from his downline.','unilevel-mlm-pro')?></p>	
		
		<p><strong><?php _e('Type - Free','unilevel-mlm-pro');?></strong> - <?php _e('A member registering with a Free ePin shall be treated as a Special Paid member of the system. The commission for the member will NOT be distributed amongst his upline (since he has not paid himself). However, he will earn commissions and bonuses from his downline (for regular paid members).','unilevel-mlm-pro')?></p>	
		
	</div>


<div><?php if(isset($message)&&$message==1){?><span style="color:#4F8A10;font-weight:bold;">
<?php echo $epin_settings['number_of_epins'];?> 
<?php _e('ePins of type ','unilevel-mlm-pro');?>
<?php if(isset($epin_settings['epins_type'])&&$epin_settings['epins_type']=='1')
	{
		echo 'Regular';
	}
	else
	{ 
		echo 'Free';
	}
?>
<?php _e(' have been generated successfully.','unilevel-mlm-pro');?>

<br>
<?php _e('Click','unilevel-mlm-pro');?>&nbsp;<a href="<?php echo admin_url()."admin.php?page=admin-reports&tab=epinreports"?>"><?php _e('<strong>Here</strong>','unilevel-mlm-pro');?></a>&nbsp;
<?php _e('to go to the ePin Report to see the listing of generated ePins.','unilevel-mlm-pro');?>
</span>
<?php } else if(isset($message)&&$message==0){?>
<span style="color:#D8000C;"><?php _e('There was an error in generating the ePins. Please try again.','unilevel-mlm-pro');?></span><?php }?></div>
<div class="fileedit-sub">
	<div class="alignleft">
	<form method="post" action="#" id="form" onSubmit="return epinValidation();">
		<table width="100%" border="0" cellspacing="4" cellpadding="4">
			
			
			<tr>
				<td><strong><?php _e('No. of ePins','unilevel-mlm-pro');?></strong></td>
				<td>:&nbsp;</td>
				<td><input type="text" name="number_of_epins" id="number_of_epins" /></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><strong><?php _e('Type','unilevel-mlm-pro');?></strong></td>
				<td>:&nbsp;</td>
				<td>
				<?php $selected='selected="selected"';?>
					<select name="epins_type" id="epins_type">
						<option value="1" <?php if(isset($epin_settings['epins_type'])&&$epin_settings['epins_type']=='1'){echo $selected;}else{ echo '';}?>><?php _e("Regular","unilevel-mlm-pro")?></option>
						<option value="0" <?php if(isset($epin_settings['epins_type'])&&$epin_settings['epins_type']=='0'){echo $selected;}else{ echo '';}?>><?php _e("Free","unilevel-mlm-pro")?></option>
					</select>
				</td>
				<td>&nbsp;</td>
			</tr>
					
			
		</table>
		<p class="submit">
		<input type="submit" name="epin_save" value="<?php _e('Generate ePins','unilevel-mlm-pro')?>"  class="button-primary" /></p>
		</form>
	</div>
</div>
</div>
<?php
}

?>