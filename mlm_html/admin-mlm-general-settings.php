<?php
function mlmGeneral()
{
	global $wpdb;	
	//get database table prefix
	$table_prefix = mlm_core_get_table_prefix();
	
	$error = '';
	$chk = 'error';
	
	//most outer if condition
	if(isset($_POST['mlm_general_settings']))
	{
		$currency = sanitize_text_field( $_POST['currency'] );
		
		$levels = sanitize_text_field( $_POST['mlm-level'] );
		
		$single_sale = sanitize_text_field( $_POST['single-sale'] );

		if ( checkInputField($currency) ) 
			$error .= "\n Please Select your currency type.";
			
		if ( checkInputField($levels) ) 
			$error .= "\n Please specify No. of Levels.";

        if ( checkInputField($single_sale) ) 
			$error .= "\n Please specify value of single sale.";			

        $wp_check= $_POST['wp_reg'];
		$reg_url = sanitize_text_field($_POST['reg_url']);
        if($wp_check=='1')
		{
        if (checkInputField($reg_url))
            $error .= "\n Please Fill The URL.";
		}
		//if any error occoured
		if(!empty($error))
			$error = nl2br($error);
		else
		{

			$chk = '';
			update_option('wp_mlm_general_settings', $_POST);
			$url = get_bloginfo('url')."/wp-admin/admin.php?page=admin-settings&tab=eligibility";
			_e("<script>window.location='$url'</script>");
			$msg = "<span style='color:green;'>Your general settings has been successfully updated.</span>";
		}
	}// end outer if condition
?>

	<script type="text/javascript">

	jQuery(document).ready(function () {

		jQuery("input[name='ePin_activate']").change(function () {
			var value = jQuery(this).val();
			if (value == '1') {
				jQuery(".sole_id").show();
			   
			} else if (value == '0') {

			   jQuery(".sole_id").hide();
			}
		});
		
		});
	</script>	
   <script language="javascript">
        jQuery(document).ready(function(){
            jQuery("#reg_url").click(function(){
                jQuery("#reg_url").removeAttr("readonly");
            });
             
        });
        function CheckBoxChanged(checkbox)
        {
            if (checkbox.checked == true) {
                //document.getElementById('reg_url').disabled = false;
                jQuery("#reg_url").removeAttr("readonly");
            }
            else
            {
                jQuery("#reg_url").attr("readonly","readonly");
                //document.getElementById('reg_url').focus();
            }
        }
        function show1()
        {
            if (document.getElementById('reg_url').value == '')
            {
                alert('Please Fill The URL');
                document.getElementById('reg_url').focus();
                return true;
            }
        }


    </script>
<?php	
	if($chk!='')
	{
		$mlm_settings = get_option('wp_mlm_general_settings');
		
		$URL=empty($mlm_settings['affiliate_url']) ? '' : $mlm_settings['affiliate_url'].'/';
?>
		
<div class='wrap1'>
	<h2><?php _e('Currency Setting','unilevel-mlm-pro');?> </h2>
	    
		<div class="updated fade">
		<p><?php _e("In order to enable SEO Friendly Affiliate URLs please add the following line of code in your .htaccess file at the top of the file BEFORE the #Begin Wordpress line of code<br/><br/> <strong> RedirectMatch 301 u/(.*)  ".site_url()."/".$URL."?sp_name=$1 </strong> <br/><br/>Please note that your Permalink setting in WordPress should be anything other than Default setting.",'unilevel-mlm-pro');?> </p> </div> <br/>
		
		<div class="notibar msginfo">
		<a class="close"></a>	
		<p><strong><?php _e('Currency','unilevel-mlm-pro');?> -</strong> <?php _e('Please select the base currency of your MLM Network. This option is very important as all calculations will be performed in this base currency. Once this currency is chosen and saved, it CANNOT be changed later. The entire network will need to be reset if you decide to change the currency at a later date.','unilevel-mlm-pro');?> </p>
		<p><strong><?php _e('No. of Levels','unilevel-mlm-pro');?> -</strong> <?php _e('This defines the levels upto which the payment will be distributed for a successful sale in the network. As with currency, once defined, this value cannot be changed.','unilevel-mlm-pro');?></p>
		<p><strong><?php _e('Value of Single Sale','unilevel-mlm-pro');?> -</strong> <?php _e('This is the value of the product being sold through the network.','unilevel-mlm-pro');?></p>

		<p><strong><?php _e('Activate ePin','unilevel-mlm-pro');?> -</strong> <?php _e('In case you would like to Activate ePin functionality on your website, set this value to Yes.','unilevel-mlm-pro');?> </p>
	    <p><strong><?php _e('Sole Payment Method','unilevel-mlm-pro');?> -</strong> <?php _e('In case members can only register on your site via ePin, set this to Yes. This would make the ePin field mandatory on the user registration form and a visitor would need a valid unused ePin to complete his registration. If this value is set to No, a visitor will be able to register on the site even without specifying a valid ePin. In this case you would need to manually mark the member as Paid / Unpaid under Users -> All Users. ','unilevel-mlm-pro');?> </p>
	    <p><strong><?php _e('ePin Length','unilevel-mlm-pro');?> -</strong> <?php _e('The length of the generated ePins.','unilevel-mlm-pro');?> </p>
	
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
	<form name="admin_general_settings" method="post" action="">
	<table border="0" cellpadding="0" cellspacing="0" width="60%" class="form-table">
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onClick="toggleVisibility('admin-mlm-currency');"><?php _e('Currency','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
			<?php
				$sql = "SELECT iso3, currency 
											FROM {$table_prefix}mlm_currency 
											ORDER BY iso3";
				$results = $wpdb->get_results($sql);
			?>
				<select name="currency" id="currency" >
					<option value=""><?php _e('Select Currency','unilevel-mlm-pro');?></option>
				<?php
					
					foreach($results as $row)
					{
						if($_POST['currency']==$row->iso3)
							$selected = 'selected';
						else
							$selected = '';
				?>
						<option value="<?= $row->iso3;?>" <?= $selected?>><?= $row->iso3." - ".$row->currency;?></option>
				<?php
					}
				?>
				</select>
				<div class="toggle-visibility" id="admin-mlm-currency"><?php _e('Select your currency which will you use.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onClick="toggleVisibility('admin-mlm-level');"><?php _e('No. of Levels','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="text" name="mlm-level" id="mlm-level" size="10" value="<?php if(!empty($_POST['mlm-level'])) _e(htmlentities($_POST['mlm-level']));?>">
				<div class="toggle-visibility" id="admin-mlm-level"><?php _e('Please specify no. of level.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onClick="toggleVisibility('admin-mlm-single-sale');"><?php _e('Value of Single Sale','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="text" name="single-sale" id="single-sale" size="10" value="<?php if(!empty($_POST['single-sale'])) _e(htmlentities($_POST['single-sale']));?>">
				<div class="toggle-visibility" id="admin-mlm-single-sale"><?php _e('Please specify value of single sale.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		
                        <tr>

                            <th scope="row" class="admin-setting" >
                                <strong><?php _e('Use WP registration page', 'unilevel-mlm-pro'); ?></strong>
                            </th>
                            <td>
                                <input type="checkbox" name="wp_reg" id="wp_reg" value="1" <?php echo ($_POST['wp_reg'] == 1) ? ' checked="checked"' : ''; ?> onclick="CheckBoxChanged(this);" onblur="show1();" />
                            </td> 
                        </tr>
                        <tr>

                            <th scope="row" class="admin-setting" >
                                <strong><?php _e('URL of registration page', 'unilevel-mlm-pro'); ?><span style="color:red;"></span>:</strong>
                            </th>
                            <td>
                                <?= site_url() . '/' ?><input type="text" name="reg_url" id="reg_url" value="<?= empty($_POST['reg_url']) ? '' : $_POST['reg_url'] ?>" readonly="true"/>
                            </td>

                        </tr>
		
	                      <tr>

                            <th scope="row" class="admin-setting" >
                                <strong><?php _e('Redirect Affiliate URL', 'unilevel-mlm-pro'); ?>:</strong>
                            </th>
                            <td>
                                <?= site_url() . '/' ?><input type="text" name="affiliate_url" id="affiliate_url" value="<?= empty($_POST['affiliate_url']) ? '' : $_POST['affiliate_url'] ?>" />
                            </td>

                        </tr>
						
			 <tr><td colspan="2" style="padding: 0px;">
		
		<table>
                <tr>
			<th scope="row" class="admin-settings">
				<strong><?php _e('Activate ePin','unilevel-mlm-pro');?> <span style="color:red;">*</span>:</strong>
			</th>
			<td>
			<?php if($mlm_settings['ePin_activate']=='0') { ?>
		<script>	jQuery(document).ready(function () { jQuery(".sole_id").hide(); });</script>
		<?php } ?>
		
		
			<input class="radio" type="radio" name="ePin_activate" value="1" <?php if(isset($_POST['ePin_activate'])&&$_POST['ePin_activate']=='1'){echo 'checked';}?>/> <?php _e('Yes', 'unilevel-mlm-pro');?>
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
			<input class="radio" type="radio" name="ePin_activate" value="0" <?php if(isset($_POST['ePin_activate'])&&$_POST['ePin_activate']=='0'){echo 'checked';}?>/> <?php _e('No', 'unilevel-mlm-pro');?>
			<?php }?>
			</td>
		</tr>
		<tr class="sole_id" >
			<th scope="row" class="admin-settings">
				<strong><?php _e('Sole Payment Method','unilevel-mlm-pro');?> <span style="color:red;">*</span>:</strong>
			</th>
			<td>
			<input class="radio" type="radio" name="sol_payment" value="1" <?php if(isset($_POST['sol_payment'])&&$_POST['sol_payment']=='1'){echo 'checked';}?>/> <?php _e('Yes', 'unilevel-mlm-pro');?>
			<input class="radio" type="radio" name="sol_payment" value="0" <?php if(isset($_POST['sol_payment'])&&$_POST['sol_payment']=='0'){echo 'checked';}?>/> <?php _e('No', 'unilevel-mlm-pro');?>
			</td>
		</tr>
		<tr class="sole_id">
				<th><strong><?php _e('ePin Length','unilevel-mlm-pro');?></strong></th>
				
				<td>
				<?php $epin_length= $_POST['epin_length']?>
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
			
			
                          </td></tr>	
		
	</table>
	<p class="submit">
	<input type="submit" name="mlm_general_settings" id="mlm_general_settings" value="<?php _e('Update Options', 'unilevel-mlm-pro');?> &raquo;" class='button-primary' onClick="needToConfirm = false;">
	</p>
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
		
		
		<?php  if($mlm_settings['recur_payment']=='0') { ?>
		<script>	jQuery(document).ready(function () { jQuery(".recur_id").hide(); });</script>
		<?php } ?>
		 
			<form name="admin_general_settings" method="post" action="">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onClick="toggleVisibility('admin-mlm-currency');"><?php _e('Currency','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
			<?php
				$sql = "SELECT iso3, currency 
						FROM {$table_prefix}mlm_currency
						WHERE iso3 = '".$mlm_settings['currency']."'
						ORDER BY iso3";
				//$sql = mysql_fetch_array(mysql_query($sql));
			?>
				<input type="text" name="currency" id="currency" value="<?= $mlm_settings['currency']?>" readonly />
				<div class="toggle-visibility" id="admin-mlm-currency"><?php _e('You can not change the currency.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onClick="toggleVisibility('admin-mlm-level');"><?php _e('No. of Levels','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="text" name="mlm-level" id="mlm-level" size="10" value="<?php if(!empty($mlm_settings['mlm-level'])) _e($mlm_settings['mlm-level']);?>" readonly>
				<div class="toggle-visibility" id="admin-mlm-level"><?php _e('You can not change the level.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onClick="toggleVisibility('admin-mlm-single-sale');"><?php _e('Value of Single Sale','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="text" name="single-sale" id="single-sale" size="10" value="<?php if(!empty($mlm_settings['single-sale'])) _e($mlm_settings['single-sale']);?>">
				<div class="toggle-visibility" id="admin-mlm-single-sale"><?php _e('Please specify value of single sale.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		

                    <tr>

                        <th scope="row" class="admin-setting" >
                            <strong><?php _e('Use WP registration page', 'unilevel-mlm-pro'); ?></strong>
                        </th>
                        <td>
                            <input type="checkbox" name="wp_reg" id="wp_reg" value="1" <?php echo ($mlm_settings['wp_reg'] == 1) ? ' checked="checked"' : ''; ?>  onclick="CheckBoxChanged(this);"/>
                        </td> 



                    </tr>
                    <tr>

                        <th scope="row" class="admin-setting" >
                            <strong><?php _e('URL of registration page', 'unilevel-mlm-pro'); ?><span style="color:red;"></span>:</strong>
                        </th>
                        <td>
                            <?= site_url() . '/' ?><input type="text" name="reg_url" id="reg_url" value="<?= empty($mlm_settings['reg_url']) ? '' : $mlm_settings['reg_url'] ?>" onblur="show1()" readonly="true" />
                        </td>

                    </tr>

					
						                      <tr>

                            <th scope="row" class="admin-setting" >
                                <strong><?php _e('Redirect Affiliate URL', 'unilevel-mlm-pro'); ?>:</strong>
                            </th>
                            <td>
                                <?= site_url() . '/' ?><input type="text" name="affiliate_url" id="affiliate_url" value="<?= empty($mlm_settings['affiliate_url']) ? '' : $mlm_settings['affiliate_url'] ?>" />
                            </td>

                        </tr>

		
		
		<tr><td colspan="2" style="padding: 0px;">
		<table>
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
				<th><strong><?php _e('ePin Length','unilevel-mlm-pro');?></strong></th>
				
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
                    </td></tr>
		</table>
		<p class="submit">
	<input type="submit" name="mlm_general_settings" id="mlm_general_settings" value="<?php _e('Update Options', 'unilevel-mlm-pro');?> &raquo;" class='button-primary' onClick="needToConfirm = false;">
	</p>
	</form>
	</div>
		<?php
		}
	} // end if statement
	else
		 _e($msg);
} //end mlmGeneral function
?>