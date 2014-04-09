<?php 
require_once("php-form-validation.php");
function mlm_my_financial_dashboard_page($id=''){
global $table_prefix;
global $wpdb;
global $date_format;
global $current_user; 

$mlm_payouts =  get_option('wp_mlm_payout_settings'); 
$url = plugins_url();
$userKey = get_current_user_key();
$user_id = getuseridbykey($userKey);
$user_name = getusernamebykey($userKey);

$mlm_currency = get_option('wp_mlm_general_settings');
 $currency=$mlm_currency['currency'];       
 
$balance = $wpdb->get_var("SELECT closing_bal FROM {$table_prefix}mlm_transaction  WHERE id = (select max(id) from {$table_prefix}mlm_transaction where user_id='".$user_id."')");
$withdrwl_bal=$wpdb->get_var("SELECT SUM(round(amount+withdrawal_fee+witholding_tax)) as total FROM {$table_prefix}mlm_withdrawal  where payment_processed=0 AND user_id='".$user_id."'");
$Left_balance=$balance-$withdrwl_bal;
$Left_balance=number_format($Left_balance,2,'.','');
if(isset($_POST['submit'])){

//Check if already request for withdrawal 

	$wamount= empty($_POST['wamount'])?'':$_POST['wamount'];
	$wcomment= empty($_POST['wcomment'])?'':$_POST['wcomment'];
	$withdrawalMode= empty($_POST['withdrawalMode'])?'':$_POST['withdrawalMode'];
	$waddress= empty($_POST['address'])?'':" - \n".$_POST['address'];
    $other= empty($_POST['other'])?'':$_POST['other'];
	
         // Settings for Minimum Amount can withdraw By User
        $minAmt = unserialize(stripcslashes($_POST['withdrawalMode']));  
        if($minAmt[3]!=''){$min_amount = $minAmt[3]; }

         
	
	$error ='';
        if ( checkInputField($wamount) ) 
                $error .= "</br> Please enter your Amount.";
		if ( checkInputField($withdrawalMode)) 
                $error .= "</br> Please Select Any Payment mode.";		
        if ($wamount>$balance) 
                $error .= "</br> Amount should be less or equal your Balance Amount.";	
        if ($wamount>($balance-$withdrwl_bal) && isset($withdrwl_bal))
		     $error .= "</br> Your Withdrawal Request for Amount ".$withdrwl_bal." is Pending.You have not sufficient Amount for this Withdrawal Request.";
        if($wamount<$min_amount  && isset($min_amount))
		 $error .= "</br> Withdrawal Amount should be Minimum ".$currency." ".$min_amount;	
				

		if(empty($error))
		{
        
        
        $other_deduc = get_option('wp_mlm_other_method_settings');
		if(!empty($other_deduc)){
		
        for($i=0;$i<=count($other_deduc['othr_mthd'])-1;$i++){
        // $other_deduc['othr_mthd'][$i],$other_deduc['othr_amt'][$i],$other_deduc['othr_type'][$i];
        if($other_deduc['othr_type'][$i]=='percent'){ 
		$othr_mthd[] =  $other_deduc['othr_mthd'][$i];
		$deduct_amount[] = $wamount* $other_deduc['othr_amt'][$i]/100; 
		$deduct_type[] =  $other_deduc['othr_type'][$i];
		$wamount = $wamount-$deduct_amount[$i];
		
		}
        if($other_deduc['othr_type'][$i]=='fixed'){
		$othr_mthd[] =  $other_deduc['othr_mthd'][$i];
		$deduct_amount[] =  $other_deduc['othr_amt'][$i]; 
		$deduct_type[] =  $other_deduc['othr_type'][$i];
		$wamount = $wamount-$deduct_amount[$i];
		}
		
         }
		 $other_method_serialize=array($othr_mthd,$deduct_amount,$deduct_type);
		 //print_r(//$other_method_serialize); die;
        $otherDeduct = array_sum($deduct_amount);
        }
		else
		{
		$other_method_serialize=0;
		$otherDeduct=0;
		}
		
        $wdrAmount = $wamount;
        $deduct = unserialize(stripcslashes($_POST['withdrawalMode']));  
        if($deduct[2]=='percent'){ $deduct_amount = $wdrAmount* $deduct[1]/100; }
        if($deduct[2]=='fixed'){$deduct_amount =  $deduct[1]; }
        
        
        
        $wdr_amount = $wdrAmount - $deduct_amount;  
         $sql = "INSERT {$table_prefix}mlm_withdrawal SET 
            `withdrawal_initiated`=1, 
            `withdrawal_initiated_comment` = '".$wcomment."', 
            `withdrawal_initiated_date` = NOW() , 
            `withdrawal_mode` = '".$deduct[0].$waddress."',
			`other_method` = '".serialize($other_method_serialize)."',
            `user_id` = '".$user_id."' ,
            `amount`='".$wdr_amount."',
            `withdrawal_fee`='".$deduct_amount."',
            `witholding_tax`='".$otherDeduct."'"; 
        $wpdb->query($sql);
		
		//Generate Mail to Admin Regarding With-drawal Amount
		WithDrawalProcessMail($user_id,$_POST);
		
        $sucsess = 'Your Withdrawal Request Initiated';
        $sucsess .= '</br>Thanks for Patience';
        unset($wamount, $wcomment, $withdrawalMode, $waddress, $_POST['radio']); 
        }
	
	
}


?>
<script>
	    $(function() {
		$('#delivery').hide();
                $('#other').hide();
                $('#withdrawalMode').change(function(){ 
                $('#delivery').hide();
                
                $('#other').hide();
                $('#' + $(this).val()).show();
        });
    });
</script>
<style>.payout-summary tr { margin:10px 0px; }</style>
	<!--<script src="initiate.js" type="text/javascript"></script>-->
		<span style='color:red;'><?=!empty($error)?$error:''?></span>
        <span style='color:green;'><?=  !empty($sucsess)?$sucsess:''; ?></span>
		<form name="form" action="" method="post" >
        <table width="100%" border="0" cellspacing="10" cellpadding="1" class="payout-summary">
		<tr><td><strong>User Name:</strong>&nbsp;&nbsp;&nbsp;<strong><?= $user_name ?></strong></td>
        <td><strong>Account Balance:</strong>&nbsp;&nbsp;&nbsp;<strong><?= $currency ?><?= empty($balance)?'0.00':$balance ?></strong></td></tr>
  
<tr><td>&nbsp;</td>
        <td><strong>Withdrawable Balance:</strong>&nbsp;&nbsp;&nbsp;<strong><?= $currency ?><?= empty($Left_balance)?'0.00':$Left_balance ?></strong></td></tr>
  
				<tr>
                <td><strong>Withdrawal Amount</strong></td>
                <td width="50%"><input type="text" name="wamount" id="wamount"  onblur="return numeric(this.value,'wamount');" value="<?=empty($wamount)?'':$wamount ?>"  size="21" /></td>
                </tr>
                <td><strong>Comment </strong></td>
				<td width="50%" ><textarea name="wcomment" id="wcomment"><?=empty($wcomment)?'':$wcomment ?></textarea></td>
                </tr>
                <tr>
                <td><strong>Withdrawal Mode </strong></td>
                <td width="50%" ><select name="withdrawalMode" id="withdrawalMode"  onblur="" >
                        
                <option value="">Select Option</option>
                <?php 
                $deduction = get_option('wp_mlm_withdrawal_method_settings');
                for($i=0;$i<=count($deduction['withdwl_mthd'])-1;$i++){
                $value = array($deduction['withdwl_mthd'][$i],$deduction['withdwl_amt'][$i],$deduction['withdwl_type'][$i],$deduction['min_amount'][$i]);
                $values = serialize($value); ?>
                <option value='<?= $values ?>' <?php if(isset($_POST['withdrawalMode'])){ if($deduction['withdwl_mthd'][$i]==$minAmt[0]){ echo 'selected';}  }?>><?= $deduction['withdwl_mthd'][$i] ?></option>
                <?php } ?>
                </select></td></tr>
                <tr><td>&nbsp;</td><td><textarea  name="address"   onblur="return allowspace(this.value,'delivery');" id="delivery" placeholder="Enter your Address" ></textarea></td></tr>
            <tr><td>&nbsp;</td><td><input type="text"  name="other"   onblur="return allowspace(this.value,'delivery');" id="other"  /></td></tr>
           <tr><td>&nbsp;</td><td><input type="submit"name="submit" id="submit" value="Submit"  /></td></tr>
			</table></form>
        <form method="post" action="" >
        <table><tr><td><input type="submit" name="view" value="View Transaction history"></td><td></td></tr></table>
        </form>
<?php 
       if(isset($_POST['view']) && $_POST['view'] === 'View Transaction history'){
       $result = $wpdb->get_results("select * from {$table_prefix}mlm_transaction where user_id={$user_id} order by transaction_date ASC");
      $records = array_reverse($result);
       $totalrecords = count($records); 
	   
	  //$othr_method = $wpdb->get_var("select other_method from {$table_prefix}mlm_withdrawal where user_id ='1' AND id=21");
		  // print_r(unserialize($othr_method));
   $rowcount=0;    
?>
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load('visualization', '1', {packages: ['table']});
</script>
<script type="text/javascript">
var visualization;
var data;
var options = {'showRowNumber': false};
function drawVisualization() {
  // Create and populate the data table.
  var dataAsJson =
  {cols:[
	{id:'A',label:'<?= _e("Date","unilevel-mlm-pro"); ?>',type:'string'},
	{id:'B',label:'<?= _e("Opening Bal","unilevel-mlm-pro"); ?>',type:'string'},
	{id:'C',label:'<?= _e("Dr. Amount","unilevel-mlm-pro"); ?>',type:'string'},
        {id:'C',label:'<?= _e("Cr.Amount","unilevel-mlm-pro"); ?>',type:'string'},
        {id:'C',label:'<?= _e("Closing Bal","unilevel-mlm-pro"); ?>',type:'string'},
	{id:'D',label:'<?= _e("Comment","unilevel-mlm-pro"); ?>',type:'string'}],
  rows:[
   <?php foreach( $records as $row) : 
       if($row->transaction_type==1) $type='deposit';
       if($row->transaction_type==2) $type='Withdrawal';
       
       if($row->dr_id!='0'){
   	   
           $withdrawal_fee = $wpdb->get_var("select withdrawal_fee from {$table_prefix}mlm_withdrawal where user_id ='{$row->user_id}' AND id='{$row->dr_id}'");
           $witholding_tax = $wpdb->get_var("select witholding_tax from {$table_prefix}mlm_withdrawal where user_id ='{$row->user_id}' AND id='{$row->dr_id}'");
		   $othrs_method='';
		   $othrs_method = $wpdb->get_var("select other_method from {$table_prefix}mlm_withdrawal where user_id ='{$row->user_id}' AND id='{$row->dr_id}'");
		   
		   $othr_method=unserialize($othrs_method);
        } 
       //print_r($othr_method);exit;
       $wf = empty($withdrawal_fee)?'0.0':$withdrawal_fee;
       $wt = empty($witholding_tax)?'0.0':$witholding_tax;
       $type = !empty($type)?'0.0':$type;
	   $wf_comment=empty($withdrawal_fee)?'':'Withdrawal Processing Fee';
	   $wt_comment=empty($witholding_tax)?'':'Other Deductions:';
        ?>
			
<?php

if(!empty($wt) && $wt!='0.0') {
$amt=0;
for($i=0;$i<count($othr_method[1]);$i++)
{

if($i==0){
$wt_opening=$row->closing_bal+$othr_method[1][$i];
$closing=$row->closing_bal;
}

if($i>0)
{
$amt=$amt+$othr_method[1][$i-1];
$wt_opening=$row->closing_bal+$othr_method[1][$i]+$amt;

$closing=$row->closing_bal+$amt;
}

$rowcount++;
	 ?>
      {c:[{v:'<?= DefaultDateFormat($row->transaction_date) ?>'},
                        {v:'<?= number_format($wt_opening, 2, '.', '') ?>'},
                        {v:'<?= number_format($othr_method[1][$i], 2, '.', '') ?>'},
                        {v:'<?= number_format($row->cr_amount, 2, '.', '') ?>'},
                        {v:'<?= number_format($closing, 2, '.', '') ?>'},
                        {v:'<?= 'Other Deductions: '.$othr_method[0][$i]?>'}]},
<?php
}

	}?>	
			
	<?php 
	 if(!empty($wf) && $wf!='0.0') {
	 $rowcount++;
	 ?>
	{c:[{v:'<?= DefaultDateFormat($row->transaction_date) ?>'},
                        {v:'<?=number_format($row->closing_bal+$wf+$wt, 2, '.', '') ?>'},
                        {v:'<?=number_format($wf, 2, '.', '') ?>'},
                        {v:'<?=number_format($row->cr_amount, 2, '.', '') ?>'},
                        {v:'<?= number_format($row->closing_bal+$wt, 2, '.', '') ?>'},
                        {v:'<?=$wf_comment ?>'}]},
	<?php } ?>		
		{c:[{v:'<?= DefaultDateFormat($row->transaction_date) ?>'},
                        {v:'<?=number_format($row->opening_bal, 2, '.', '')?>'},
                        {v:'<?=number_format($row->dr_amount-$wf-$wt, 2, '.', '') ?>'},
                        {v:'<?=number_format($row->cr_amount, 2, '.', '') ?>'},
                        {v:'<?= number_format($row->closing_bal+$wf+$wt, 2, '.', '') ?>'},
                        {v:'<?=$row->comment?>'}]},

	
	<?php $rowcount++;
        unset($withdrawal_fee,$witholding_tax,$fundTransDeduct);
        endforeach ;?>  
  ]};
  data = new google.visualization.DataTable(dataAsJson);
  // Set paging configuration options
  // Note: these options are changed by the UI controls in the example.
  options['page'] = 'enable';
  options['pageSize'] = 10;
  options['pagingSymbols'] = {prev: 'prev', next: 'next'};
  options['pagingButtonsConfiguration'] = 'auto';
  //options['allowHtml'] = true;
  //data.sort({column:1, desc: false});
  // Create and draw the visualization.
  visualization = new google.visualization.Table(document.getElementById('table'));
  draw();
}
function draw() {
  visualization.draw(data, options);
}
google.setOnLoadCallback(drawVisualization);
// sets the number of pages according to the user selection.
function setNumberOfPages(value) {
  if (value) {
	options['pageSize'] = parseInt(value, 10);
	options['page'] = 'enable';
  } else {
	options['pageSize'] = null;
	options['page'] = null;  
  }
  draw();
}
// Sets custom paging symbols "Prev"/"Next"
function setCustomPagingButtons(toSet) {
  options['pagingSymbols'] = toSet ? {next: 'next', prev: 'prev'} : null;
  draw();  
}
function setPagingButtonsConfiguration(value) {
  options['pagingButtonsConfiguration'] = value;
  draw();
}
</script>
<!--va-matter-->
    <div class="va-matter">
    	<!--va-matterbox-->
    	<div class="va-matterbox">
        	<!--va-headname-->
        	<div class="va-headname"><?php _e('My Transaction History (All figures below are in '.$currency.')','unilevel-mlm-pro');?></div>
            <!--/va-headname-->
			<div class="va-admin-leg-details">
            	<!--va-admin-mid-->
				<div class="paging">
				  <form action="">
					<div class="left-side">
						<?php _e('Display Number of Rows','unilevel-mlm-pro');?> : &nbsp; 
					</div>
					<div class="right-side">
						<select style="font-size: 12px" onchange="setNumberOfPages(this.value)">
						  <option value="5">5</option>
						  <option selected="selected" value="10">10</option>
						  <option value="20">20</option>
						  <option  value="50">50</option>
						  <option value="100">100</option>
						  <option value="500">500</option>
						   <option value="">All</option>
						</select>
					</div>	
					</form>
					<div class="right-members">
					<?php _e('Total Records','unilevel-mlm-pro');?>: <strong><?= $rowcount; ?></strong>
					</div>
					<div class="va-clear"></div>
				  </div>
				<div id="table"></div>
				<div class="va-clear"></div>
			</div>		
		</div>
	</div>	
<?php } ?>
<?php 	} ?>