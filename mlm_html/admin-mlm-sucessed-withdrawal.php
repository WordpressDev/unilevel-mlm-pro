<?php 
function mlm_withdrawal_sucess(){
?>

<div class='wrap'>
	<div id="icon-users" class="icon32"></div><h1><?php _e('Processed Withdrawals Report','unilevel-mlm-pro');?></h1><br />
		<div class="notibar msginfo" style="margin:10px;">
			<a class="close"></a>
			<p><?php _e('Given below is the list of all withdrawal requests that have been successfully processed.','unilevel-mlm-pro');?></p>
		</div>	
</div>

<?php

	require_once('withdrawals-list-table.php');
	$objOrderList = new Withdrawals_List_Table();
	$objOrderList->prepare_items();
	$objOrderList->display();

}
 
?>