<?php

require_once("php-form-validation.php");



function register_user_html_page()
{

	global $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	$error = '';
	$chk = 'error';
	global $current_user;
	get_currentuserinfo();
    $user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

		
		if(!empty($_GET['sp_name']))
		   {
		
		   $sp_name= $_GET['sp_name'];
		   // error_reporting(0);
	       ?>
		 <script>$.cookie('s_name','<?= $sp_name ?>',{ path: '/' });</script>
	       
	       <?php 
		   //setcookie("s_name", $sp_name);
		   }
		   else if(!empty($_GET['sp']))
		   {
		     $sp_name = getusernamebykey($_GET['sp']); 
		 ?>
		 <script>$.cookie('s_name','<?= $sp_name ?>',{ path: '/' });</script>
	       
	       <?php 
		   }
	       else 
		   {
	       $sp_name= $_COOKIE["s_name"];
	       }
		     //echo $_COOKIE["s_name"]."hello";
		//get no. of level
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
	 $mlm_pay_settings = get_option('wp_mlm_payment_settings'); 
	 
	 $mlm_method = get_option('wp_mlm_payment_method');
	 
	if(is_user_logged_in())
	{
	
		$sponsor_name = $current_user->user_login;
		$readonly_sponsor = 'readonly';
	    
		$spnsr_set=1;
	}

	else if(isset($_REQUEST['sp_name']) &&  $_REQUEST['sp_name'] != ''){
		
		 //$sponsorName = getusernamebykey($_REQUEST['sp']); 
		 $sponsorName = $_REQUEST['sp_name'];	
		if(isset($sponsorName) && $sponsorName !='' )		
		{
			$readonly_sponsor = 'readonly';
			$sponsor_name = $sponsorName;
		}else{
			
			redirectPage(home_url(), array()); exit; 

		}
		
	}
		else if(isset($_COOKIE["s_name"]) && $_COOKIE["s_name"] !='')
	{
			$readonly_sponsor = 'readonly';
			$sponsor_name = $_COOKIE["s_name"];
	
	}
		else if(isset($_REQUEST['sp']) &&  $_REQUEST['sp'] != ''){
		
		 //$sponsorName = getusernamebykey($_REQUEST['sp']); 
		 $sponsorName = getusernamebykey($_REQUEST['sp']);	
		if(isset($sponsorName) && $sponsorName !='' )		
		{
			$readonly_sponsor = 'readonly';
			$sponsor_name = $sponsorName;
		}else{
			
			redirectPage(home_url(), array()); exit; 

		}
		
	}
	else{
	
	   // $sponsor_name = get_top_level_user();
		//$readonly_sponsor = 'readonly';
		$readonly_sponsor = '';
	}
	

	//most outer if condition
	if(isset($_POST['submit']))
	{
		$firstname = sanitize_text_field( $_POST['firstname'] );
		$lastname = sanitize_text_field( $_POST['lastname'] );
		$username = sanitize_text_field( $_POST['username'] );
		
		/******* check for the epin field ******/
		if(isset($_POST['epin']) && !empty($_POST['epin']))
		{
			$epin = sanitize_text_field( $_POST['epin'] );
		}
		else if(isset($_POST['epin']) && empty($_POST['epin']))
		{		
				
				$epin = '';
		}
	
		/******* check for the epin field ******/
		
		$password = sanitize_text_field( $_POST['password'] );
		$confirm_pass = sanitize_text_field( $_POST['confirm_password'] );
		$email = sanitize_text_field( $_POST['email'] );
		$confirm_email = sanitize_text_field( $_POST['confirm_email'] );
		$sponsor = sanitize_text_field( $_POST['sponsor'] );
		/*$address1 = sanitize_text_field( $_POST['address1'] );
		$address2 = sanitize_text_field( $_POST['address2'] );
		city = sanitize_text_field( $_POST['city'] );
		$state = sanitize_text_field( $_POST['state'] );
		$postalcode = sanitize_text_field( $_POST['postalcode'] );
		$telephone = sanitize_text_field( $_POST['telephone'] );
		$dob = sanitize_text_field( $_POST['dob'] );*/
		
		
		//Add usernames we don't want used
		$invalid_usernames = array( 'admin' );
		//Do username validation
		$username = sanitize_user( $username );
		
		if(!validate_username($username) || in_array($username, $invalid_usernames)) 
			$error .= "\n Username is invalid.";
			
		if ( username_exists( $username ) ) 
			$error .= "\n Username already exists.";
		
		/******* check for the epin field ******/
		if(isset($epin) && !empty($epin))
		{
		
			if ( epin_exists( $epin ) ) 
				{	$error .= "\n ePin already issued or wrong ePin.";
				}
		}
		if($mlm_general_settings['sol_payment']==1)
		{
		if(isset($_POST['epin']) && empty($_POST['epin']))
		{
		$error .= "\n Please enter your ePin.";
		}
		
		}
		/******* check for the epin field ******/
		
		if ( checkInputField($password) ) 
			$error .= "\n Please enter your password.";
			
		if ( confirmPassword($password, $confirm_pass) ) 
			$error .= "\n Please confirm your password.";
		

		//Do e-mail address validation
		if ( !is_email( $email ) )
			$error .= "\n E-mail address is invalid.";
			
		if (email_exists($email))
			$error .= "\n E-mail address is already in use.";
		
		if ( confirmEmail($email, $confirm_email) ) 
			$error .= "\n Please confirm your email address.";

		
		if ( checkInputField($firstname) ) 
			$error .= "\n Please enter your first name.";
			
		if ( checkInputField($lastname) ) 
			$error .= "\n Please enter your last name.";

		if ( checkInputField($sponsor) && !empty($sponsor) ) 
			$error .= "\n Please enter your sponsor name.";

					
		/*if ( checkInputField($address1) ) 
			$error .= "\n Please enter your address.";
			
		if ( checkInputField($city) ) 
			$error .= "\n Please enter your city.";
			
		if ( checkInputField($state) ) 
			$error .= "\n Please enter your state.";
			
		if ( checkInputField($postalcode) ) 
			$error .= "\n Please enter your postal code.";
			
		if ( checkInputField($telephone) ) 
			$error .= "\n Please enter your contact number.";

		if ( checkInputField($dob) ) 
			$error .= "\n Please enter your date of birth.";*/
		

		
		//Case If User is not fill the Sponser field
		if(empty($_POST['sponsor']))
		{		
		$sponsor = get_top_level_user();
		}
		
		$sql = "SELECT COUNT(*) num, `user_key` 
				FROM {$table_prefix}mlm_users 
				WHERE `username` = '".$sponsor."'";
		$intro = $wpdb->get_row($sql);
		
		
				
		//generate random numeric key for new user registration
		$user_key = generateKey();
		//if generated key is already exist in the DB then again re-generate key
		do
		{
			$check = $wpdb->get_var("SELECT COUNT(*) ck 
													FROM {$table_prefix}mlm_users 
													WHERE `user_key` = '".$user_key."'");
			$flag = 1;
			if($check == 1)
			{
				$user_key = generateKey();
				$flag = 0;
			}
		}while($flag==0);
		

		// outer if condition
		if(empty($error))
		{
			// inner if condition
			if($intro->num==1)
			{
				$sponsor = $intro->user_key;
				
				$sponsor1 = $sponsor;
				//find parent key
				if(!empty($_GET['k']) && $_GET['k']!='')
				{
				
					$parent_key = $_GET['k'];
				}
				else
				{
					$readonly_sponsor = '';
					
					$parent_key = $sponsor;
				}
			
				$user = array
				(
					'user_login' => $username,
					'user_pass' => $password,
					'first_name' => $firstname,
					'last_name' => $lastname,
					'user_email' => $email,
					'role' => 'mlm_user'
				);
				
				// return the wp_users table inserted user's ID
				$user_id = wp_insert_user($user);
				
				//get the selected country name from the country table
				$country = $_POST['country'];
				$sql = "SELECT name 
						FROM {$table_prefix}mlm_country
						WHERE id = '".$country."'";
				$country1 = $wpdb->get_var($sql);
				
				//insert the registration form data into user_meta table
				
				/*add_user_meta( $user_id, 'user_address1', $address1, FALSE ); 
				add_user_meta( $user_id, 'user_address2', $address2, FALSE );
				add_user_meta( $user_id, 'user_city', $city, FALSE );
				add_user_meta( $user_id, 'user_state', $state, FALSE );
				add_user_meta( $user_id, 'user_country', $country1, FALSE );
				add_user_meta( $user_id, 'user_postalcode', $postalcode, FALSE );
				add_user_meta( $user_id, 'user_telephone', $telephone, FALSE );
				add_user_meta( $user_id, 'user_dob', $dob, FALSE);*/
				
				/*Send e-mail to admin and new user - 
				You could create your own e-mail instead of using this function*/
				wp_new_user_notification($user_id, $password);
			   
			   
			    if(!empty($epin))
				{
					$pointResult = mysql_query("select point_status from {$table_prefix}mlm_epins where epin_no = '{$epin}'");
					$pointStatus = mysql_fetch_row($pointResult);
					// to epin point status 1 
					if($pointStatus[0]=='1')
					{	
						$paymentStatus='1'; 
					}
					// to epin point status 1 
					else if($pointStatus[0]=='0')
					{
						$paymentStatus = '2'; 
					}
				}
				else
				{	// to non epin 
					$paymentStatus = '0'; 
				}
			   
			   
				//insert the data into fa_user table
				$insert = "INSERT INTO {$table_prefix}mlm_users
						   (
								user_id, username, user_key, parent_key, sponsor_key, payment_status
							) 
							VALUES
							(
								'".$user_id."','".$username."', '".$user_key."', '".$parent_key."', '".$sponsor."','".$paymentStatus."'
							)";
							
				$wpdb->query($insert);
				
				//hierarchy code for genology 
			     InsertHierarchy($user_key,$sponsor);
				
				
				
				if(isset($epin) && !empty($epin))
					{
						$sql = "update {$table_prefix}mlm_epins set user_key='{$user_key}', date_used=now(), status=1 where epin_no ='{$epin}' "; 
						// Update epin according user_key (19-07-2013)
						
                          mysql_query($sql); 
                         if($paymentStatus==1)
						 {
						
						UserStatusUpdate($user_id);
						
						 }
					}
				
				$chk = '';
				$msg = "<span style='color:green;'>Congratulations! You have successfully registered in the system.</span>";

				$check_paid = $wpdb->get_var("SELECT payment_status FROM {$table_prefix}mlm_users WHERE user_id = '".$user_id."'");
				
				if($check_paid=='0')
				{
				 PayNowOptions($user_id,'register_user');
				 }
				 			
			} //end inner if condition
			else
				$error =  "\n Sponsor does not exist in the system.";
		}//end outer if condition
	}//end most outer if condition
	
	//if any error occoured
	if(!empty($error))
		$error = nl2br($error);

	if($chk!='')
	{
?>

 
<script type="text/javascript">
var popup1,popup2,splofferpopup1;
var bas_cal, dp_cal1,dp_cal2, ms_cal; // declare the calendars as global variables 
window.onload = function() {
	dp_cal1 = new Epoch('dp_cal1','popup',document.getElementById('dob'));  
};

function checkUserNameAvailability(str)
{
	//alert(url); return true; 
		
	if(isSpclChar(str, 'username')==false)
	{
		document.getElementById('username').focus();
		return false;
	}
	var xmlhttp;    
	if (str=="")
  	{
  		alert("Please enter the user name.");
		document.getElementById('username').focus();
		return false;
  	}
	
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	if (xmlhttp.status==200 && xmlhttp.readyState==4)
	{
	 document.getElementById("check_user").innerHTML=xmlhttp.responseText;
	 //alert(xmlhttp.responseText);
	}
	}   
	
	xmlhttp.open("GET", "<?= MLM_PLUGIN_URL.'ajax/check_username.php'?>"+"?action=username&q="+str,true);
	xmlhttp.send();
}



function checkReferrerAvailability(str)
{ 
	if(isSpclChar(str, 'sponsor')==false)
	{
		document.getElementById('sponsor').focus();
		return false;
	}
	var xmlhttp;    
	
	if (str!="") {
	
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	if (xmlhttp.status==200 && xmlhttp.readyState==4)
	{
	 document.getElementById("check_referrer").innerHTML=xmlhttp.responseText;
	}
	}
	xmlhttp.open("GET", "<?= MLM_PLUGIN_URL.'ajax/check_username.php'?>"+"?action=sponsor&q="+str,true);
	xmlhttp.send();

	}
}

function checkePinAvailability(str)
{
	var iChars = "~`!@#$%^&*()+=[]\\\';,- ./{}|\":<>?abcdefghijklmnopqrstuvwxyz";
	for (var i = 0; i < str.length; i++)
	{
    	if (iChars.indexOf(str.charAt(i)) != -1) 
		{
    		alert("<?php _e('Please enter Valid ePin.','unilevel-mlm-pro')?>");
			document.getElementById('epin').value='';
			document.getElementById('epin').focus();
    		return false;
        }
    }
	
	var xmlhttp;    
	if (str=="")
  	{
  		alert("<?php _e('Please enter ePin.','unilevel-mlm-pro')?>");
		document.getElementById('epin').focus();
		return false;
  	}
	
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	if (xmlhttp.status==200 && xmlhttp.readyState==4)
	{
		document.getElementById("check_epin").innerHTML=xmlhttp.responseText;
	 //alert(xmlhttp.responseText);
	}
	}   
	
	xmlhttp.open("GET", "<?= plugins_url().'/'.MLM_PLUGIN_NAME.'/ajax/check_epin.php'?>"+"?q="+str,true);
	xmlhttp.send();
}



function checkePinAvailability1(str)
{
	var iChars = "~`!@#$%^&*()+=[]\\\';,- ./{}|\":<>?abcdefghijklmnopqrstuvwxyz";
	for (var i = 0; i < str.length; i++)
	{
    	if (iChars.indexOf(str.charAt(i)) != -1) 
		{
    		alert("<?php _e('Please enter Valid ePin.','unilevel-mlm-pro')?>");
			document.getElementById('epin').value='';
			document.getElementById('epin').focus();
    		return false;
        }
    }
	
	var xmlhttp;    
	/*if (str=="")
  	{
  		alert("Please enter ePin.");
		document.getElementById('epin').focus();
		return false;
  	}*/
	
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	if (xmlhttp.status==200 && xmlhttp.readyState==4)
	{
		document.getElementById("check_epin").innerHTML=xmlhttp.responseText;
	 //alert(xmlhttp.responseText);
	}
	}   
	
	xmlhttp.open("GET", "<?= plugins_url().'/'.MLM_PLUGIN_NAME.'/ajax/check_epin.php'?>"+"?q="+str,true);
	xmlhttp.send();
}
</script>

        <?php
        $general_setting = get_option('wp_mlm_general_settings');
		if ( is_user_logged_in() ) {
		 if (!empty($general_setting['wp_reg']) && !empty($general_setting['reg_url']) && $user_role!='mlm_user'){
            echo "<script>window.location ='" . site_url() . '/' . $general_setting['reg_url'] . "'</script>"; }
		}	
		else {
		if (!empty($general_setting['wp_reg']) && !empty($general_setting['reg_url'])){
            echo "<script>window.location ='" . site_url() . '/' . $general_setting['reg_url'] . "'</script>"; }
		}
        ?>
		
		
<span style='color:red;'><?=$error?></span>
<?php if(isset($msg) && $msg!="") echo $msg;?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<form name="frm" method="post" action="" onSubmit="return formValidationNewVer();">
		<tr>
			<td><?php _e('Create Username','unilevel-mlm-pro');?><span style="color:red;">*</span> :</td>
			<td><input type="text" name="username" id="username" value="<?php if(!empty($_POST['username']))  _e( htmlentities($_POST['username'])); ?>" maxlength="20" size="37" onBlur="checkUserNameAvailability(this.value);"><br /><div id="check_user"></div></td>
		</tr>
		<?php 
				
		if(isset($mlm_general_settings['ePin_activate'])&&$mlm_general_settings['ePin_activate']=='1'&&isset($mlm_general_settings['sol_payment'])&&$mlm_general_settings['sol_payment']=='1'){?>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td><?php _e('Enter ePin','unilevel-mlm-pro');?><span style="color:red;">*</span> :</td>
			<td><input type="text" name="epin" id="epin" value="<?php if(!empty($_POST['epin']))  _e( htmlentities($_POST['epin'])); ?>" maxlength="20" size="37" onBlur="checkePinAvailability(this.value);"><br /><div id="check_epin"></div></td>
		</tr>
		<?php } else if(isset($mlm_general_settings['ePin_activate']) && $mlm_general_settings['ePin_activate']=='1'){?>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td><?php _e('Enter ePin','unilevel-mlm-pro');?> :</td>
			<td><input type="text" name="epin" id="epin" value="<?php if(!empty($_POST['epin']))  _e( htmlentities($_POST['epin'])); ?>" maxlength="20" size="37" onBlur="checkePinAvailability1(this.value);"><br /><div id="check_epin"></div></td>
		</tr>
		<?php }?>
		<tr><td colspan="2">&nbsp;</td></tr>
		
		<tr>
			<td><?php _e('Create Password','unilevel-mlm-pro') ?> <span style="color:red;">*</span> :</td>
			<td>	<input type="password" name="password" id="password" maxlength="20" size="37" >
				<br /><span style="font-size:12px; font-style:italic; color:#006633"><?php _e('Password length atleast 6 character','unilevel-mlm-pro');?></span>
			</td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>
		
		<tr>
			<td><?php _e('Confirm Password','unilevel-mlm-pro') ?>  <span style="color:red;">*</span> :</td>
			<td><input type="password" name="confirm_password" id="confirm_password" maxlength="20" size="37" ></td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>

		
		<tr>
			<td><?php  _e('Email Address','unilevel-mlm-pro');?> <span style="color:red;">*</span> :</td>
			<td><input type="text" name="email" id="email" value="<?php if(!empty($_POST['email']))  _e(htmlentities($_POST['email']));?>"  size="37" ></td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr><tr>
		
		<tr>
			<td><?php  _e('Confirm Email Address','unilevel-mlm-pro');?> <span style="color:red;">*</span> :</td>
			<td><input type="text" name="confirm_email" id="confirm_email" value="<?php if(!empty($_POST['confirm_email']))  _e(htmlentities($_POST['confirm_email']));?>" size="37" ></td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>
		
		<tr>
			<td><?php  _e('First Name','unilevel-mlm-pro');?> <span style="color:red;">*</span> :</td>
			<td><input type="text" name="firstname" id="firstname" value="<?php if(!empty($_POST['firstname']))  _e(htmlentities($_POST['firstname'])); ?>" maxlength="20" size="37" onBlur="return checkname(this.value, 'firstname');" ></td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>
		
		<tr>
			<td><?php  _e('Last Name','unilevel-mlm-pro');?> <span style="color:red;">*</span> :</td>
			<td><input type="text" name="lastname" id="lastname" value="<?php if(!empty($_POST['lastname'])) _e(htmlentities($_POST['lastname']));?>" maxlength="20" size="37" onBlur="return checkname(this.value, 'lastname');"></td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>
		
		<tr>
			<?php
			if(isset($sponsor_name) && $sponsor_name!='')
			{
				$spon = $sponsor_name;
			}
			else if(isset($_POST['sponsor']))
				$spon =  htmlentities($_POST['sponsor']);
			?>
			<td><?php  _e('Sponsor Name','unilevel-mlm-pro');?> <span style="color:red;">*</span> :</td>
			<td>
			<input type="text" name="sponsor" id="sponsor" value="<?php if(!empty($spon)) _e($spon);?>" maxlength="20" size="37" onBlur="checkReferrerAvailability(this.value);" <?= $readonly_sponsor;?>>
			<br /><div id="check_referrer"></div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			
			<input type="submit" name="submit" id="submit" value="<?php _e('Submit','unilevel-mlm-pro')?>" /></td>
		</tr>
	</form>
</table>
<?php
	}
	else
		 _e($msg);
		 

	 
}//function end

?>
