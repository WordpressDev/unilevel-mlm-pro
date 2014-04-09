<?php

require_once("php-form-validation.php");
require_once("admin-mlm-general-settings.php");
require_once("admin-mlm-eligibility-settings.php");
require_once("admin-mlm-payout-settings.php");
require_once("admin-mlm-regular-bonus-settings.php");
require_once("admin-mlm-royalty-bonus-settings.php");
require_once("admin-mlm-deduction-settings.php");
require_once("admin-create-first-user.php");
require_once("admin-mlm-payout-run.php");
require_once("admin-mlm-epin-settings.php");

function adminMLMSettings()
{	
	global $pagenow, $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	$mlm_settings = get_option('wp_mlm_general_settings');	
	$sql = "SELECT COUNT(*) AS num FROM {$table_prefix}mlm_users";
	$num = $wpdb->get_var($sql);
	
	if($num == 0)
	{
		$tabs = array( 
						'createuser' => __('Create First User','unilevel-mlm-pro'), 
						'general' => __('General','unilevel-mlm-pro') ,
						'eligibility' => __('Eligibility','unilevel-mlm-pro') ,
						'payout' => __('Payout','unilevel-mlm-pro') ,
						'regular_bonus' => __('Regular Bonus','unilevel-mlm-pro'),
						'royalty_bonus' => __('Royalty Bonus','unilevel-mlm-pro'),
						'deduction' => __('Deductions','unilevel-mlm-pro')
						
						
						);
		if(isset($mlm_settings['ePin_activate'])&&$mlm_settings['ePin_activate']=='1')
		{
			$tabs['epin_settings'] = __('ePins','unilevel-mlm-pro');
		}
		$tabs['license_detail'] = __('License Detail','unilevel-mlm-pro');
        $tabs['reset_all_data'] = __('Reset All MLM Data','unilevel-mlm-pro');	 		
		$tabval = 'createuser';
		$tabfun = 'register_first_user';
	}
	else
	{
		$tabs = array(
						'general' => __('General','unilevel-mlm-pro') ,
						'eligibility' => __('Eligibility','unilevel-mlm-pro') ,
						'payout' => __('Payout','unilevel-mlm-pro') ,
						'regular_bonus' => __('Regular Bonus','unilevel-mlm-pro'),
						'royalty_bonus' => __('Royalty Bonus','unilevel-mlm-pro'),
						'deduction' => __('Deductions','unilevel-mlm-pro')
						
						
					  ); 
		if(isset($mlm_settings['ePin_activate'])&&$mlm_settings['ePin_activate']=='1')
		{
			$tabs['epin_settings'] = __('ePins','unilevel-mlm-pro');
		}
		$tabs['license_detail'] = __('License Detail','unilevel-mlm-pro');
		  $tabs['reset_all_data'] = __('Reset All MLM Data','unilevel-mlm-pro');	 			  
		$tabval = 'general';
		$tabfun = 'mlmGeneral';
	}
	if(!empty($_GET['tab'])){
	if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'createuser')
		$current = 'createuser';
	else if(  $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'general')
		$current = 'general';
	else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'eligibility')
		$current = 'eligibility';
	else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'payout')
		$current = 'payout';
	else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'regular_bonus')
		$current = 'regular_bonus';
		else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'royalty_bonus')
		$current = 'royalty_bonus';
		else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'deduction')
		$current = 'deduction';
	else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'license_detail')
		$current = 'license_detail';
	else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'epin_settings')
		$current = 'epin_settings';	
	else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'reset_all_data')
		$current = 'reset_all_data';
	}	
	else
 		$current = $tabval;
		
    $links = array();
	  
	_e('<div id="icon-themes" class="icon32"><br></div>');
	 _e("<h1>MLM Settings</h1>","unilevel-mlm-pro");
    _e('<h2 class="nav-tab-wrapper">');
    foreach( $tabs as $tab => $name )
	{
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        _e("<a class='nav-tab$class' href='?page=admin-settings&tab=$tab'>$name</a>");    
    }
    _e('</h2>');
	if(!empty($_GET['tab'])){
	if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'createuser')
			register_first_user();
	else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'general')
		 mlmGeneral();
	else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'eligibility')
		mlmEligibility();
	else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'payout')
		mlmPayout();
	else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'regular_bonus')
		mlmRegularBonus();
		else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'royalty_bonus')
		mlmRoyaltyBonus();
		else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'deduction')
		mlmDeduction();
	else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'license_detail')
		mlm_licenese_settings();
    else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'epin_settings')
       epin_tab();
	else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'reset_all_data')
            adminMlmReserAllData();		  
		}
	else
		 $tabfun();
		 
}//end function






?>