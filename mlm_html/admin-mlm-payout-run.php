<?php 
function adminMLMPayout()
{
	$msg = '';
	$displayData = '';
	
	if(isset($_REQUEST['distribute_commission_bonus']))
	{

		$msg .= mlmDistributeBonus();
		$msg .= '&nbsp;Distributed Successfully';
 
	}
    	
	if(isset($_REQUEST['pay_cycle']))
	{	
		$payoutArr = payoutRun(); 
		 
	}
	
	if(isset($_REQUEST['pay_actual_amount']))
	{	
		$msg = wpmlm_run_pay_cycle();		
	}
?>
<div class='wrap'>
	<div id="icon-users" class="icon32"></div><h1><?php _e('MLM Payout','unilevel-mlm-pro');?></h1><br />

	<div class="notibar msginfo">
		<a class="close"></a>
		<p>	<?php _e('The commissions and bonuses would not show up in member account till the time the Payout Routine is not run. This script can be run manually once every week, every fortnight or every month depending on the payout cycle of your network.','unilevel-mlm-pro');?>
		
		</p>
		
		<p><?php _e('Alternately, you can also schedule (cron job) the following URL as per the frequency of the payout cycle.','unilevel-mlm-pro');?></p>
		<p><?= MLM_URL ?>/cronjobs/paycycle.php</p>
		
		<?= payoutLicMsg() ?>
		
	</div>
	<div style="font-size:18px; padding:10px; color:#0000CC; "><?php if(!empty($payoutArr['directRun'])) _e($payoutArr['directRun'])?><?php if(!empty($msg)) _e($msg) ?></div>	


		
		<form name="frm" method="post" action="">
		
		<!--	<div class="payout-run">
				<input class="button-primary" type="submit" name="distribute_commission_bonus" value="<?php //_e('Distribute Bonus','unilevel-mlm-pro');?>" id="distribute_commission_bonus" /> 
			</div>-->
				
			<div class="payout-run">
				<input class="button-primary" type="submit" name="pay_cycle" value="<?php _e('Run Payout Routine','unilevel-mlm-pro');?>" id="pay_cycle" /> 
			</div>
			<!-- Dislay data -->
			<?php if(!empty($payoutArr['displayData']) && $payoutArr['displayData'] != '')
			{?>
				<table width="100%" border="1" cellspacing="0" cellpadding="0" align="left" style="margin:20px 0 20px 0">
<tr>
	<th scope="row"><?php _e('S.No','unilevel-mlm-pro');?></th>
		<th scope="row"><?php _e('Username','unilevel-mlm-pro');?></th>
			<th scope="row"><?php _e('Name','unilevel-mlm-pro');?></th>
			<th scope="row"><?php _e('Commission','unilevel-mlm-pro');?></th>
           
			<th scope="row"><?php _e('Bonus','unilevel-mlm-pro');?></th>

			<th scope="row"><?php _e('Net Amount','unilevel-mlm-pro');?></th>
		  </tr>
		  
		<?php 
		if($payoutArr['displayData'] != 'None'){
				$i = 1;
			foreach( $payoutArr['displayData'] as $row)   
		{	
			?>
			  <tr>
				<td align="center"><?= $i; ?></td>
				<td align="center"><?= $row['username']; ?></td>
				<td align="center"><?= $row['first_name']." ".$row['last_name']; ?></td>
				<td align="center"><?= number_format($row['commission'], 2, '.','');?></td>
                
				<td align="center"><?= number_format($row['bonus'], 2, '.','');?></td>

				<td align="center"><?= number_format($row['net_amount'], 2, '.','');?></td>
			  </tr>
		   	  <?php $i++; 
		    }
		    }else{
			?>
			<tr>
				<td colspan="8" align="center"><?php _e('There is no any eligible member Found in this Payout.','unilevel-mlm-pro');?> </td>
			</tr>
			<?php 
			}
		?>
	   </table><br>
	   <div class="payout-run" style="float:right;">
				<input class="button-primary" type="submit" name="pay_actual_amount" value="<?php _e('All is Well. Commit.','unilevel-mlm-pro');?>" id="pay_actual_amount" /> 
			</div>
			<div class="payout-run" style="float:right;">
				<a class="button-primary" href="?page=mlm-payout" ><?php _e('Something wrong. Cancel.','unilevel-mlm-pro');?></a> 
			</div>
				<?php }
			
			?>
			<!-- End display data -->
			<div style="clear:both;"></div>	
	
		</form>
				
	
</div>
<?php 
}
?>