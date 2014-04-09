

<div class="wrap">
			<div id="icon-users" class="icon32"><br/></div>
			<h2><?php _e('Earning Reports','unilevel-mlm-pro');?></h2>
</div>

<?php

	global $wpdb; 
	global $table_prefix;
	global $date_format;
	$mlm_settings = get_option('wp_mlm_general_settings');
	$product_price=$mlm_settings['single-sale'];
	$currency = $mlm_settings['currency'];
	
	 extract($_REQUEST);
		if(isset($datefrom) &&!empty($datefrom))
		{
			$datefrom1=explode("/",$datefrom);
			$datefromfinal=$datefrom1[2].'-'.$datefrom1[1].'-'.$datefrom1[0].' 00:00:00';
			$timestamp = mktime(0, 0, 0, $datefrom1[1]);
			$month_name= date("F", $timestamp);
			$from=	$datefrom1[0] .' '.$month_name.','.$datefrom1[2];
		}
		else
		{
			$year=date('Y');
			$month=date('m');
			$day=01;
			$datefromfinal=$year.'-'.$month.'-'.$day.' 00:00:00';
			$timestamp = mktime(0, 0, 0, $month);
			$month_name= date("F", $timestamp);
			$from=	$day .' '.$month_name.','.$year;
		}
		if(isset($dateto) &&!empty($dateto))
		{
			$dateto1=explode("/",$dateto);
			$datetofinal=$dateto1[2].'-'.$dateto1[1].'-'.($dateto1[0].' 23:59:59');
			$timestamp = mktime(0, 0, 0, $dateto1[1]);
			$month_name= date("F", $timestamp);
			
			$to=	$dateto1[0] .' '.$month_name.','.$dateto1[2];
		}
		else
		{
			$year=date('Y');
			$month=date('m');
			$day=date('d');
			$datetofinal=$year.'-'.$month.'-'.($day.' 23:59:59');
			$timestamp = mktime(0, 0, 0, $month);
			$month_name= date("F", $timestamp);
			$to=	$day .' '.$month_name.','.$year;
		}
		if(isset($datefromfinal) && isset($datetofinal))
		{
		$between="AND wu.user_registered BETWEEN '$datefromfinal' AND '$datetofinal'";
		$between1="AND date BETWEEN '$datefromfinal' AND '$datetofinal'";
		$date_used="AND date_used BETWEEN '$datefromfinal' AND '$datetofinal'";
		}
		else
		{
			$between='';
			$between1='';
			$date_used='';
		}
	
	//$total_amount = $wpdb->get_var("select sum(epin_value) from {$table_prefix}mlm_epins where status=1 $date_used");
      $firstUser=get_top_level_user();
	  $firstuser_id = $wpdb->get_var("SELECT user_id FROM {$table_prefix}mlm_users WHERE username = '".$firstUser."'");
	  
	  
	$total_paid_users=$wpdb->get_var("SELECT count(*) as total from {$table_prefix}mlm_users as mu INNER JOIN {$table_prefix}users as wu ON mu.user_id = wu.ID WHERE mu.user_id!='".$firstuser_id."' AND payment_status='1' $between");
	
	$firstu_id = $wpdb->get_var("SELECT id FROM {$table_prefix}mlm_users WHERE username = '".$firstUser."'");
	$payout_paid=$wpdb->get_var("SELECT sum(total_amt) as amount from {$table_prefix}mlm_payout WHERE user_id!='".$firstu_id."' $between1");
	
	if(isset($total_paid_users))
	{
	$total_paid_users=$total_paid_users;
	$total_amount=$total_paid_users*$product_price;
	}
	else
	{
		$total_paid_users='0';
		$total_amount='0';
	}
	
	if(isset($payout_paid))
	{
	$payout_paid=$payout_paid;
	}
	else
	{
		$payout_paid='0';
	}
	
	$net_earning  = $total_amount-$payout_paid;

?>
<div>&nbsp;</div>

<div>&nbsp;</div>
<script type="text/javascript">
                      var popup1,popup2,splofferpopup1;
                      var bas_cal, dp_cal11,dp_cal2, ms_cal; // declare the calendars as global variables 
                      window.onload = function() {
                              dp_cal11 = new Epoch('dp_cal11','popup',document.getElementById('datefrom'));
                              dp_cal12 = new Epoch('dp_cal12','popup',document.getElementById('dateto'));  							
                      };
                      </script>
<form id="processed_report" method="GET" action="">
	<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
	 <input type="hidden" name="tab" value="earningreports" />
         <div style='width:300px;margin-left:30%'>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                <td align='left'><strong><?php _e('Date From','unilevel-mlm-pro');?></strong><strong>:</strong></td>
                <td align='right'><input type="text" name="datefrom" id="datefrom"></td>
                </tr>
                <tr><td><br/></td></tr>
                <tr>
                <td align='left'><strong><?php _e('Date To','unilevel-mlm-pro');?></strong><strong>:</strong></td>
                <td align='right'><input type="text" name="dateto" id="dateto"></td>
                </tr>
                <tr><td><br/></td></tr>
                <tr>
                <td></td>
                <td align='right' colspan='2'>
                    <input type="reset" name="reset" value="Reset" onclick="window.location='<?admin_url()?>'admin.php/?page=admin-mlm-reports&tab=earningreports" style="float:right;">
                    <input type="submit" name="submit" value=" Go " style="float:right;"></td>
                </tr>
                <tr>
                <td><div align="center"></div></td>
                </tr>
                </table>
        </div>

</form>
<div>&nbsp;</div>
<?php
echo '<strong>Period:</strong> '.$from.' to '.$to;;
?>
<div>&nbsp;</div>
<table cellspacing="0" class="wp-list-table widefat fixed toplevel_page_admin-mlm-reports">
    
    <tr><td><strong><?php _e('Gross Earnings :','unilevel-mlm-pro');?></strong>&nbsp;&nbsp;<?php echo $currency." ".$total_amount;?><br/><br/></td></tr>
<tr><td><strong><?php _e('Payouts :','unilevel-mlm-pro');?></strong>&nbsp;&nbsp;<?php echo $currency." ".$payout_paid;?><br/><br/></td></tr>
<tr><td><strong><?php _e('Net Earnings :','unilevel-mlm-pro');?></strong>&nbsp;&nbsp;<?php echo $currency." ".$net_earning;?><br/><br/></td></tr>

	
</table>