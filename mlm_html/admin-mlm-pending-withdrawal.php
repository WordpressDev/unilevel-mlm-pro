<?php 
function mlm_withdrawal_request(){
global $table_prefix;
global $wpdb;
global $date_format;
$url = plugins_url();
?>
<div class='wrap'>
	<div id="icon-users" class="icon32"></div><h1><?php _e('Pending User Withdrawals','unilevel-mlm-pro');?></h1><br />

	<div class="notibar msginfo" style="margin:10px;">
		<a class="close"></a>
		<p><?php _e('Given below is the list of all pending User Withdrawals.','unilevel-mlm-pro');?></p>
        <p><strong><?php _e('Process','unilevel-mlm-pro');?></strong> - <?php _e("Input the payment details for the withdrawal. These payment details would also show up on the User's Payout Details Page.",'unilevel-mlm-pro');?> </p>
        <p><strong><?php _e('Delete','unilevel-mlm-pro');?></strong> - <?php _e('This would mark the withdrawal as deleted. The user would need to initiate a fresh withdrawal for this payout from his interface.','unilevel-mlm-pro');?> </p>
	</div>	

</div>
<?php
		
		 $sql = "SELECT  id,user_id,round(amount+withdrawal_fee+witholding_tax)as amount,DATE_FORMAT(`withdrawal_initiated_date`,'%d %b %Y')withdrawal_initiated_date, withdrawal_initiated_comment, withdrawal_mode 
		 FROM {$table_prefix}mlm_withdrawal WHERE  withdrawal_initiated= 1 AND `payment_processed`= 0";
		 
		 
		$rs = mysql_query($sql);
		
		 $payoutDetail=array();
		 $html_output="<table border='1' cellspacing='0' cellpadding='5' width='99%' style='border-color:#dadada;'>";
		 $html_output.="<tr><th>".__('Member Username','unilevel-mlm-pro')."</th><th>".__('Member Email','unilevel-mlm-pro')."</th><th>".__('Withdrawal Initiate Date','unilevel-mlm-pro')
		 ."</th><th>".__('Withdrawal Comment','unilevel-mlm-pro')."</th><th>".__('Amount','unilevel-mlm-pro')."</th><th>".__('Payment Mode ','unilevel-mlm-pro')."</th><th>".__('Action','unilevel-mlm-pro')."</th></tr>";
		 if(mysql_num_rows($rs)>0)
		 {
		 	while($row = mysql_fetch_array($rs)){
			
			$sql1 = "SELECT {$table_prefix}mlm_users.username AS uname , {$table_prefix}users.user_email AS uemail FROM {$table_prefix}users,{$table_prefix}mlm_users 
			WHERE {$table_prefix}mlm_users.username = {$table_prefix}users.user_login AND {$table_prefix}mlm_users.id = '".$row['user_id']."'"; 
				
			$res1 = mysql_query($sql1);
			$row1 = mysql_fetch_array($res1);		
			$payoutDetail['id'] = $row['id'];
			$payoutDetail['memberId'] = $row['user_id'];
			$payoutDetail['name'] = $row1['uname']; 
			$payoutDetail['email'] = $row1['uemail']; 			
			$payoutDetail['withdrawalMode'] = $row['withdrawal_mode'];
			$withdrawal_date = date_create($row['withdrawal_initiated_date']);
			$payoutDetail['widate'] = date_format($withdrawal_date,$date_format);
			$payoutDetail['wicomment'] = $row['withdrawal_initiated_comment'];
			$payoutDetail['netamount'] = round(ceil($row['amount'] * 100) / 100,2);
			//$payoutDetail['netamount'] = round($row['amount'], 2,PHP_ROUND_HALF_UP);
			
			
			
            $html_output.="<tr><td>".$payoutDetail['name']."</td><td>".$payoutDetail['email']."</td><td>".$payoutDetail['widate']."</td><td>".$payoutDetail['wicomment']
			."</td><td>".$payoutDetail['netamount']."</td><td>".$payoutDetail['withdrawalMode']."</td>
			<td><form name='withdrawal_process' method='POST' action='".admin_url( 'admin.php' )."?page=admin-mlm-withdrawal-process' id='withdrawal_process'>
			<input type='hidden' name='id' value='".$payoutDetail['id']."'>
			<input type='hidden' name='member_name' value='".$payoutDetail['name']."'>
			<input type='hidden' name='member_id' value='".$payoutDetail['memberId']."'>
			<input type='hidden' name='withdrawalMode' value='".$payoutDetail['withdrawalMode']."'>
			<input type='hidden' name='member_email' value='".$payoutDetail['email']."'>
			<input type='hidden' name='withdrawal_amount' value='".$payoutDetail['netamount']."'>
			<input type='submit' value='".__('Process','unilevel-mlm-pro')."' id='process' name='process-amount'>
			</form>&nbsp;|&nbsp;<a class='ajax-link' id='".$payoutDetail['memberId']."$".$payoutDetail['id']."' href='javascript:void(0);'>".__('Delete','unilevel-mlm-pro')."</a></td>";
		       
		 }
		 $html_output.="</table>";
		 _e($html_output);
		
		} else { _e("Hooray! Nothing in the pipeline to be processed.",'unilevel-mlm-pro'); 		}
		?>
		
	<script type="text/javascript">
			jQuery(function(){
			jQuery(".ajax-link").click( function() {
			var b=jQuery(this).parent().parent();
			var id = jQuery(this).attr('id');
			var ids = id.split("$");
			var dataString = 'wdel_id='+ ids[0]+'&w_id='+ids[1];
			
			if(confirm("Confirm Delete withdrawal request?")){
			jQuery.ajax({ 
			type: "POST", 
			url: "<?php _e($url); ?>/unilevel-mlm-pro/mlm_html/delete_withdrawal.php",
                        data: dataString,
			cache: true,
			success: function(e)
			{	
				window.location.reload(true);
			}
			});
				return false;
			}
			});
			});
	</script>
<?php } ?>
