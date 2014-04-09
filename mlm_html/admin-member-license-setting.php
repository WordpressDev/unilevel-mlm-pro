<?php
function mlm_licenese_settings()
{	
	$error = '';
	$msg = '';
	if(isset($_REQUEST['mlm_license_settings']))
	{
		if($_REQUEST['license_key']!=''){			
			$msg = licUpdate($_REQUEST); 
		}else{
			$error = _e("Please fill the complete information.");
		}
	}

$settings = get_option('mlm_license_settings');
	
?>
<div>
<style type="text/css">
#license-form{ width:auto; margin:10px;}
#license-form .fldname{ float:left; width:130px;}
#license-form .fldvalue{ float:left;}
#license-form .fldtitle{ float:left; margin:10px 0 10px 0;}
#license-form .pairfor{ float:left; padding:3px 10px 0 10px; width:210px;}
#license-form .mainpair{ float:left; padding:0 10px 0 10px;}
.cBoth{ clear:both;}
#license-form .row{	padding:5px 0 5px 0;clear:both;}
</style>
<div class='wrap1'>
	<h2><?php _e('License Settings','unilevel-mlm-pro');?> </h2>
	
	<?php if($msg) :?>	
		<?php  _e($msg); ?>
	<?php endif; ?>
	<?php if($error) :?>
	<div class="notibar msgalert">
		<a class="close"></a>
		<p><?php _e($error); ?></p>
	</div>
	<?php endif; ?>
	
<div id="license-form">
<form name="frm" method="post" action="">

	<div class="row">
		<div class="fldtitle"><b><?php _e('License Details','unilevel-mlm-pro');?> :</b></div>
	</div>
	<div class="row">
		<div class="fldname" style="margin-top:3px;"><strong><?php _e('Domain Name','unilevel-mlm-pro');?> </strong></div>
		<div class="fldvalue"><?= siteURL() ?></div>
	</div>
	<div style="clear:both; height:20px;"></div>
	<div class="row">
		<div class="fldname" style="margin-top:3px;"><strong><?php _e('License Key','unilevel-mlm-pro');?></strong></div>
		<div class="fldvalue"><input type="text" name="license_key" size="30" value="<?php if(!empty($settings['license_key'])) _e($settings['license_key']);?>" /></div>
	</div>
	<br />
<br />

<p class="submit">
<input type="submit" name="mlm_license_settings" id="mlm_license_settings" value="<?php _e('Update Details', 'unilevel-mlm-pro');?>" class='button-primary'>
</p>
</form>
</div>

</div>


<?php 
}
?>