<?php 

function mlm_payment_settings() {

	global $wpdb;	
	//get database table prefix
	$table_prefix = mlm_core_get_table_prefix();
	
	$error = '';
	$chk = 'error';
	$pageurl = get_bloginfo('url')."/wp-admin/admin.php?page=admin-mlm-payment-settings";
	
	// save Payment Method here
	if(isset($_POST['updateoption']))
	{
	
	update_option('wp_mlm_payment_method', $_POST);
	
	}
	//Update Paypal settings
	if(isset($_POST['updatepaypaloption']))
	{
    
	update_option('wp_mlm_paypal_settings', $_POST);
	}
	
	if(isset($_POST['fieldupdate']))
	{
	
	update_option('wp_mlm_paypal_field_settings', $_POST);
	}
	
	
	
	//most outer if condition
	if(isset($_POST['mlm_payment_settings']))
	{

		$site_address = sanitize_text_field( $_POST['mlm-site-address'] );
		
		$shared_key = sanitize_text_field( $_POST['mlm-pre-shared-key'] );
		
		$merchant_id = sanitize_text_field( $_POST['mlm-merchant-id'] );
		
		$password = sanitize_text_field( $_POST['mlm-merchant-password'] );

		if ( checkInputField($site_address) ) 
			$error .= "\n Please Select your website address.";
			
		if ( checkInputField($shared_key) ) 
			$error .= "\n Please specify Pre Shared Key.";

        if ( checkInputField($merchant_id) ) 
			$error .= "\n Please specify Merchant ID.";
        
		if ( checkInputField($password) ) 
			$error .= "\n Please specify Password.";				
		
		//if any error occoured
		if(!empty($error))
			$error = nl2br($error);
		else
		{
			$chk = '';
			update_option('wp_mlm_payment_settings', $_POST);
			$url = get_bloginfo('url')."/wp-admin/admin.php?page=admin-mlm-payment-settings";
			_e("<script>window.location='$url'</script>");
			$msg = "<span style='color:green;'>Your Payment settings has been successfully updated.</span>";
		}
	}// end outer if condition
	
	if($chk!='')
	{
		$mlm_settings = get_option('wp_mlm_payment_settings');
		
		$mlm_paypal_settings=get_option('wp_mlm_paypal_payment_settings');
		
		$mlm_method = get_option('wp_mlm_payment_method');
		
		$paypal_settings=get_option('wp_mlm_paypal_settings');
		
		$paypal_field_settings=get_option('wp_mlm_paypal_field_settings');

		$paypalform=$paypal_field_settings['paypal_form'];
		
		?> 
		
<div class='wrap1'>
	<h2><?php _e('Payment Setting','unilevel-mlm-pro');?> </h2>
<table id="wpsc-payment-gateway-settings" class="wpsc-edit-module-options">
				<tbody><tr>
					<td width="50%" valign="top">
						<div class="postbox">
							<h3 class="hndle">Select Payment Gateways</h3>
							<div class="inside">
								<p>Activate the payment gateways that you want to make available to your customers by selecting them below.</p>
								<br>

<form name="admin_payment_settings" method="post" action="">

<div class="wpsc-select-gateway">
<div class="wpsc-gateway-actions">
<span class="edit">
<a class="edit-payment-module" data-gateway-id="paypal" title="Edit this Payment Gateway's Settings" href="<?= $pageurl?>&amp;payment_gateway_id=paypal">Edit</a></span></div>

<p><input type="radio" name="mlm_payment_method" value="paypal" id="mlm_paypal_method" <?php if($mlm_method['mlm_payment_method']=='paypal') { echo "checked='checked'"; } ?>>
<label for="mlm_paypal_method">Paypal</label></p></div>
					
<div class="wpsc-select-gateway">
<div class="wpsc-gateway-actions">
<span class="edit">
<a class="edit-payment-module" data-gateway-id="cardsave" title="Edit this Payment Gateway's Settings" href="<?= $pageurl?>&amp;payment_gateway_id=cardsave">Edit</a></span></div>
<p>
<input type="radio" name="mlm_payment_method" value="cardsave" id="mlm_worldpay_id" <?php if($mlm_method['mlm_payment_method']=='cardsave') { echo "checked='checked'"; } ?>>
<label for="mlm_worldpay_id">cardsave</label></p></div>


<div class="submit gateway_settings">
<input type="submit" value="Update" name="updateoption" class="button-primary">
</div>
</form>
</div>
</div>

</td>

<td id="wpsc-payment-gateway-settings-panel" class="wpsc-module-settings" rowspan="2">
									
									
									
	<?php if($error) :?>
	<div class="notibar msgerror">
		<a class="close"></a>
		<p> <strong><?php _e('Please Correct the following Error','unilevel-mlm-pro');?> :</strong> <?php _e($error); ?></p>
	</div>
	<?php endif; ?>
	
	<?php
		if((empty($mlm_settings)) && (isset($_GET['payment_gateway_id']))  && $_GET['payment_gateway_id']=='cardsave')
		{
?>
<div class="postbox">
<h3 class="hndle">Cardsave</h3>
<div class="inside">
	<form name="admin_payout_settings" method="post" action="">
	<table border="0" cellpadding="0" cellspacing="0" width="60%" class="form-table">
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('admin-mlm-site-address');"><?php _e('Website Address','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="text" name="mlm-site-address" id="mlm-site-address" size="30" value="<?php if(!empty($mlm_settings['mlm-site-address'])) _e($mlm_settings['mlm-site-address']);?>">
				<div class="toggle-visibility" id="admin-mlm-site-address"><?php _e('Please specify Website Address.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('admin-mlm-pre-shared-key');"><?php _e('Pre Shared Key','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="text" name="mlm-pre-shared-key" id="mlm-pre-shared-key" size="30" value="<?php if(!empty($mlm_settings['mlm-pre-shared-key'])) _e($mlm_settings['mlm-pre-shared-key']);?>">
				<div class="toggle-visibility" id="admin-mlm-pre-shared-key"><?php _e('Please specify value of Pre Shared Key.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('admin-mlm-merchant-id');"><?php _e('Merchant ID','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="text" name="mlm-merchant-id" id="mlm-merchant-id" size="30" value="<?php if(!empty($mlm_settings['mlm-merchant-id'])) _e($mlm_settings['mlm-merchant-id']);?>">
				<div class="toggle-visibility" id="admin-mlm-merchant-id"><?php _e('Please specify value of Merchant ID.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		
		
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('admin-mlm-merchant-password');"><?php _e('Password','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="password" name="mlm-merchant-password" id="mlm-merchant-password" size="30" value="<?php if(!empty($mlm_settings['mlm-merchant-password'])) _e($mlm_settings['mlm-merchant-password']);?>">
				<div class="toggle-visibility" id="admin-mlm-merchant-password"><?php _e('Please specify value of merchant Password.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		
		
		
	</table>
	<p class="submit">
	<input type="submit" name="mlm_payment_settings" id="mlm_payment_settings" value="<?php _e('Update Options', 'unilevel-mlm-pro');?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
	</p>
	</form>
	</div>
	
	</div></div>
	<script language="JavaScript">
  populateArrays();
</script>
<?php
		}
		else if((!empty($mlm_settings)) && (isset($_GET['payment_gateway_id']))  && $_GET['payment_gateway_id']=='cardsave')
		{
		?>
		<div class="postbox">
<h3 class="hndle">Cardsave</h3>
<div class="inside">

			<form name="admin_payout_settings" method="post" action="">
	<table border="0" cellpadding="0" cellspacing="0" width="60%" class="form-table">
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('admin-mlm-site-address');"><?php _e('Website Address','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="text" name="mlm-site-address" id="mlm-site-address" size="30" value="<?php if(!empty($mlm_settings['mlm-site-address'])) _e($mlm_settings['mlm-site-address']);?>">
				<div class="toggle-visibility" id="admin-mlm-site-address"><?php _e('Please specify Website Address.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('admin-mlm-pre-shared-key');"><?php _e('Pre Shared Key','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="text" name="mlm-pre-shared-key" id="mlm-pre-shared-key" size="30" value="<?php if(!empty($mlm_settings['mlm-pre-shared-key'])) _e($mlm_settings['mlm-pre-shared-key']);?>">
				<div class="toggle-visibility" id="admin-mlm-pre-shared-key"><?php _e('Please specify value of Pre Shared Key.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('admin-mlm-merchant-id');"><?php _e('Merchant ID','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="text" name="mlm-merchant-id" id="mlm-merchant-id" size="30" value="<?php if(!empty($mlm_settings['mlm-merchant-id'])) _e($mlm_settings['mlm-merchant-id']);?>">
				<div class="toggle-visibility" id="admin-mlm-merchant-id"><?php _e('Please specify value of Merchant ID.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		
		
		<tr>
			<th scope="row" class="admin-settings">
				<a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('admin-mlm-merchant-password');"><?php _e('Password','unilevel-mlm-pro');?> <span style="color:red;">*</span>: </a>
			</th>
			<td>
		<input type="password" name="mlm-merchant-password" id="mlm-merchant-password" size="30" value="<?php if(!empty($mlm_settings['mlm-merchant-password'])) _e($mlm_settings['mlm-merchant-password']);?>">
				<div class="toggle-visibility" id="admin-mlm-merchant-password"><?php _e('Please specify value of merchant Password.','unilevel-mlm-pro');?></div>
			</td>
		</tr>
		
		
		
		
	</table>
	<p class="submit">
	<input type="submit" name="mlm_payment_settings" id="mlm_payment_settings" value="<?php _e('Update Options', 'unilevel-mlm-pro');?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
	</p>
	</form>
	</div>
	
	
	</div></div>
		<?php
		} 

		if(((empty($paypal_settings)) && (isset($_GET['payment_gateway_id']))  && $_GET['payment_gateway_id']=='paypal') || (empty($paypal_settings) && !isset($_GET['payment_gateway_id']) ))
		{ ?>
	<div class="postbox">
<h3 class="hndle"><?php _e('Paypal','unilevel-mlm-pro');?></h3>


<div class="inside">
			<table class="form-table">
								<tbody>
<form action="" method="post" name="admin_paypal_settings" >								
								<tr>
					<td><?php _e('Display Name','unilevel-mlm-pro');?></td>
					<td>
						<input type="text" name="user_defined_name" value=""><br>
						<small><?php _e('The text that people see when making a purchase.','unilevel-mlm-pro');?></small>
					</td>
				</tr>
			
  <tr>
      <td><?php _e('Username:','unilevel-mlm-pro');?>
      </td>
      <td>
      <input type="text" size="40" value="" name="paypal_business">
      </td>
  </tr>
  <tr>
  	<td></td>
  	<td colspan="1">
  	<span class="description">
  	<?php _e('This is your PayPal email address.','unilevel-mlm-pro');?>
  	</span>
  	</td>
  </tr>

  <tr>
      <td><?php _e('Account Type:','unilevel-mlm-pro');?>
      </td>
      <td>
		<select name="paypal_multiple_url"><option value="https://www.paypal.com/cgi-bin/webscr" selected="selected"><?php _e('Live Account','unilevel-mlm-pro');?></option><option value="https://www.sandbox.paypal.com/cgi-bin/webscr"><?php _e('Sandbox Account','unilevel-mlm-pro');?></option></select>
	   </td>
  </tr>
  <tr>
	 <td colspan="1">
	 </td>
	 <td>
		<span class="description">
  			<?php _e('If you have a PayPal developers Sandbox account please use Sandbox mode, if you just have a standard PayPal account then you will want to use Live mode.','unilevel-mlm-pro');?>
  		</span>
  	  </td>
  </tr>
   <tr>
     <td><?php _e('IPN :','unilevel-mlm-pro');?>
     </td>
     <td>
       <input type="radio" value="1" name="paypal_ipn" id="paypal_ipn1"> <label for="paypal_ipn1"><?php _e('Yes','unilevel-mlm-pro');?></label> &nbsp;
       <input type="radio" value="0" name="paypal_ipn" id="paypal_ipn2"> <label for="paypal_ipn2"><?php _e('No','unilevel-mlm-pro');?></label>
     </td>
  </tr>
  <tr>
  	<td colspan="2">
  	<span class="description">
  	<?php _e('IPN (instant payment notification ) will automatically update your sales logs to \'Accepted payment\' when a customers payment is successful. For IPN to work you also need to have IPN turned on in your Paypal settings. If it is not turned on, the sales sill remain as \'Order Pending\' status until manually changed. It is highly recommend using IPN, especially if you are selling digital products.','unilevel-mlm-pro');?>
  	</span>
  	</td>
  </tr>
  <tr>
     <td style="padding-bottom: 0px;">
      <?php _e('Address Override:','unilevel-mlm-pro');?>
     </td>
     <td style="padding-bottom: 0px;">
       <input type="radio" value="1" name="address_override" id="address_override1"> <label for="address_override1"><?php _e('Yes','unilevel-mlm-pro');?></label> &nbsp;
       <input type="radio" value="0" name="address_override" id="address_override2"> <label for="address_override2"><?php _e('No','unilevel-mlm-pro');?></label>
     </td>
   </tr>
   <tr>
  	<td colspan="2">
  	<span class="wpscsmall description">
  	<?php _e('This setting affects your PayPal purchase log. If your customers already have a PayPal account PayPal will try to populate your PayPal Purchase Log with their PayPal address. This setting tries to replace the address in the PayPal purchase log with the Address customers enter on your Checkout page.','unilevel-mlm-pro');?>
  	</span>
  	</td>
   </tr>

   <tr class="update_gateway">
		<td colspan="2">
			<div class="submit">
			<input type="submit" value="Update" name="updatepaypaloption">
		</div>
		</td>
	</tr>
</form>
	<tr class="firstrowth">
		<td style="border-bottom: medium none;" colspan="2">
			<strong class="form_group"><?php _e('Forms Sent to Gateway','unilevel-mlm-pro');?></strong>
		</td>
	</tr>
<form action="" method="post" name="admin_field_settings" >
    <tr>
      <td><?php _e('First Name Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[first_name]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	  <?php echo field_option($paypalform['first_name']) ; ?>
      </select>
      </td>
  </tr>
    <tr>
      <td><?php _e('Last Name Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[last_name]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	  <?php echo field_option($paypalform['last_name']) ; ?>
      </select>      </td>
  </tr>
    <tr>
      <td><?php _e('Address Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[address]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	  <?php echo field_option($paypalform['address']) ; ?>
      </select>
      </td>
  </tr>
  <tr>
      <td><?php _e('City Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[city]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	  <?php echo field_option($paypalform['city']) ; ?>
      </select>
      </td>
  </tr>
  <tr>
      <td><?php _e('State Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[state]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	  <?php echo field_option($paypalform['state']) ; ?>
      </select>
      </td>
  </tr>
  <tr>
      <td><?php _e('Postal / ZIP Code Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[post_code]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	 <?php echo field_option($paypalform['post_code']) ; ?>
      </select>
      </td>
  </tr>
  <tr>
      <td><?php _e('Country Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[country]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	 <?php echo field_option($paypalform['country']) ; ?>
      </select>
      </td>
      </tr></tbody></table>

 <div class="submit"><input type="submit" name="fieldupdate" value="Update"></div>
</form>
 </div>
		


</div>	
	<?php	}
		else if(((!empty($paypal_settings)) && (isset($_GET['payment_gateway_id']))  && $_GET['payment_gateway_id']=='paypal') ||
	 (!empty($paypal_settings) && !isset($_GET['payment_gateway_id']) )
	)
		{
		?>
	<div class="postbox">
<h3 class="hndle"><?php _e('Paypal','unilevel-mlm-pro');?></h3>



<div class="inside">
			<table class="form-table">
								<tbody>
<form action="" method="post" name="admin_paypal_settings" >								
								<tr>
					<td><?php _e('Display Name','unilevel-mlm-pro');?></td>
					<td>
						<input type="text" name="user_defined_name" value="<?php if(!empty($paypal_settings['user_defined_name'])) _e($paypal_settings['user_defined_name'],'unilevel-mlm-pro');?>"><br>
						<small><?php _e('The text that people see when making a purchase.','unilevel-mlm-pro');?></small>
					</td>
				</tr>
			
  <tr>
      <td><?php _e('Username:','unilevel-mlm-pro');?>
      </td>
      <td>
      <input type="text" size="40" value="<?php if(!empty($paypal_settings['paypal_business'])) _e($paypal_settings['paypal_business'],'unilevel-mlm-pro');?>" name="paypal_business">
      </td>
  </tr>
  <tr>
  	<td></td>
  	<td colspan="1">
  	<span class="description">
  	<?php _e('This is your PayPal email address.','unilevel-mlm-pro');?>
  	</span>
  	</td>
  </tr>

  <tr>
      <td><?php _e('Account Type:','unilevel-mlm-pro');?>
      </td>
      <td>
		<select name="paypal_multiple_url">
		<option value="https://www.paypal.com/cgi-bin/webscr" <?php if(!empty($paypal_settings['paypal_multiple_url']) && $paypal_settings['paypal_multiple_url']=='https://www.paypal.com/cgi-bin/webscr') { ?> selected="selected" <?php } ?> ><?php _e('Live Account','unilevel-mlm-pro');?></option>
		<option value="https://www.sandbox.paypal.com/cgi-bin/webscr" <?php if(!empty($paypal_settings['paypal_multiple_url']) && $paypal_settings['paypal_multiple_url']=='https://www.sandbox.paypal.com/cgi-bin/webscr') { ?> selected="selected" <?php } ?>><?php _e('Sandbox Account','unilevel-mlm-pro');?></option></select>
	   </td>
  </tr>
  <tr>
	 <td colspan="1">
	 </td>
	 <td>
		<span class="description">
  			<?php _e('If you have a PayPal developers Sandbox account please use Sandbox mode, if you just have a standard PayPal account then you will want to use Live mode.','unilevel-mlm-pro');?>
  		</span>
  	  </td>
  </tr>
   <tr>
     <td><?php _e('IPN :','unilevel-mlm-pro');?>
     </td>
     <td>
       <input type="radio" value="1" name="paypal_ipn" id="paypal_ipn1" <?php if($paypal_settings['paypal_ipn']!='' && $paypal_settings['paypal_ipn']=='1') { ?> checked="checked" <?php } ?>> <label for="paypal_ipn1"><?php _e('Yes','unilevel-mlm-pro');?></label> &nbsp;
       <input type="radio" value="0" name="paypal_ipn" id="paypal_ipn2" <?php if($paypal_settings['paypal_ipn']!='' && $paypal_settings['paypal_ipn']=='0') { ?> checked="checked" <?php } ?>> <label for="paypal_ipn2"><?php _e('No','unilevel-mlm-pro');?></label>
     </td>
  </tr>
  <tr>
  	<td colspan="2">
  	<span class="description">
  	<?php _e('IPN (instant payment notification ) will automatically update your sales logs to \'Accepted payment\' when a customers payment is successful. For IPN to work you also need to have IPN turned on in your Paypal settings. If it is not turned on, the sales sill remain as \'Order Pending\' status until manually changed. It is highly recommend using IPN, especially if you are selling digital products.','unilevel-mlm-pro');?>
  	</span>
  	</td>
  </tr>
  <tr>
     <td style="padding-bottom: 0px;">
      <?php _e('Address Override:','unilevel-mlm-pro');?>
     </td>
     <td style="padding-bottom: 0px;">
       <input type="radio" value="1" name="address_override" id="address_override1" <?php if($paypal_settings['address_override']!='' && $paypal_settings['address_override']==1) { ?> checked="checked" <?php } ?>> <label for="address_override1"><?php _e('Yes','unilevel-mlm-pro');?></label> &nbsp;
       <input type="radio" value="0" name="address_override" id="address_override2" <?php if($paypal_settings['address_override']!='' && $paypal_settings['address_override']==0) { ?> checked="checked" <?php } ?>> <label for="address_override2"><?php _e('No','unilevel-mlm-pro');?></label>
     </td>
   </tr>
   <tr>
  	<td colspan="2">
  	<span class="wpscsmall description">
  	<?php _e('This setting affects your PayPal purchase log. If your customers already have a PayPal account PayPal will try to populate your PayPal Purchase Log with their PayPal address. This setting tries to replace the address in the PayPal purchase log with the Address customers enter on your Checkout page.','unilevel-mlm-pro');?>
  	</span>
  	</td>
   </tr>

   <tr class="update_gateway">
		<td colspan="2">
			<div class="submit">
			<input type="submit" value="Update" name="updatepaypaloption">
		</div>
		</td>
	</tr>
</form>
	<tr class="firstrowth">
		<td style="border-bottom: medium none;" colspan="2">
			<strong class="form_group"><?php _e('Forms Sent to Gateway','unilevel-mlm-pro');?></strong>
		</td>
	</tr>
<form action="" method="post" name="admin_field_settings" >
    <tr>
      <td><?php _e('First Name Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[first_name]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	  <?php echo field_option($paypalform['first_name']) ; ?>
      </select>
      </td>
  </tr>
    <tr>
      <td><?php _e('Last Name Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[last_name]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	  <?php echo field_option($paypalform['last_name']) ; ?>
      </select>      </td>
  </tr>
    <tr>
      <td><?php _e('Address Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[address]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	  <?php echo field_option($paypalform['address']) ; ?>
      </select>
      </td>
  </tr>
  <tr>
      <td><?php _e('City Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[city]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	  <?php echo field_option($paypalform['city']) ; ?>
      </select>
      </td>
  </tr>
  <tr>
      <td><?php _e('State Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[state]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	  <?php echo field_option($paypalform['state']) ; ?>
      </select>
      </td>
  </tr>
  <tr>
      <td><?php _e('Postal / ZIP Code Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[post_code]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	 <?php echo field_option($paypalform['post_code']) ; ?>
      </select>
      </td>
  </tr>
  <tr>
      <td><?php _e('Country Field','unilevel-mlm-pro');?></td>
      <td>
      <select name="paypal_form[country]">
      <option value=""><?php _e('Please choose','unilevel-mlm-pro');?></option>
	 <?php echo field_option($paypalform['country']) ; ?>
      </select>
      </td>
      </tr></tbody></table>

 <div class="submit"><input type="submit" name="fieldupdate" value="Update"></div>
</form>
 </div>
		


</div>	
		
<?php } ?>		
		</td>
						</tr>
			</tbody></table>
		<?php	} // end if statement
	else
		 _e($msg);

}
 function field_option($value) {
if($value==1)
 $a='selected="selected"';
else $a=''; 
if($value==2)
 $b='selected="selected"';
else $b='';
if($value==3)
 $c='selected="selected"';
else $c='';
if($value==4)
 $d='selected="selected"';
else $d='';
if($value==5)
 $e='selected="selected"';
else $e='';
if($value==6)
 $f='selected="selected"';
else $f='';
if($value==7)
 $g='selected="selected"';
else $g='';
if($value==8)
 $h='selected="selected"';
else $h='';
 if($value==9)
 $i='selected="selected"';
else $i='';

 $var='<option value="1" '.$a.'>Your billing/contact details</option>
	  <option value="2" '.$b.'>First Name</option>
	  <option value="3" '.$c.'>Last Name</option>
	  <option value="4" '.$d.'>Address</option>
	  <option value="5" '.$e.'>City</option>
	  <option value="6" '.$f.'>State</option>
	  <option value="7" '.$g.'>Country</option>
	  <option value="8" '.$h.'>Postal Code</option>
	  <option value="9" '.$i.'>Email</option>';

	  return $var;

	  }
?>