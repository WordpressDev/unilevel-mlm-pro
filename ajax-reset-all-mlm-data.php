<?php 
require_once('../../../wp-config.php');
global $wpdb, $table_prefix;
if($_POST['name'] == 'reset_data' && is_super_admin() )
{
	
	delete_option('wp_mlm_general_settings'); 
	delete_option('wp_mlm_eligibility_settings'); 
	delete_option('wp_mlm_payout_settings');
	delete_option('wp_mlm_regular_bonus_settings');
	delete_option('wp_mlm_royalty_bonus_settings');
	delete_option('wp_mlm_other_method_settings');
	delete_option('wp_mlm_withdrawal_method_settings');
	delete_option('wp_mlm_payment_settings');
	delete_option('wp_mlm_epin_settings');

	$theme_slug = get_option( 'stylesheet' );
	delete_option("theme_mods_$theme_slug");
        
        $user_ids = $wpdb->get_results("select user_id from {$table_prefix}mlm_users");
        $meta_keys = $wpdb->get_results("select meta_key from {$table_prefix}usermeta group by meta_key");
        foreach($user_ids as $user_id){ 
            foreach($meta_keys as $meta_key){
                delete_user_meta( $user_id->user_id, $meta_key->meta_key );
            }
        }
    $wpdb->query( "DELETE  FROM {$table_prefix}users  where ID IN (select user_id from {$table_prefix}mlm_users) " );
    $wpdb->query( "TRUNCATE TABLE {$table_prefix}mlm_users" );
	$wpdb->query( "TRUNCATE TABLE {$table_prefix}mlm_hierarchy" );
	$wpdb->query( "TRUNCATE TABLE {$table_prefix}mlm_payment_status" );
	$wpdb->query( "TRUNCATE TABLE {$table_prefix}mlm_commission" );
	$wpdb->query( "TRUNCATE TABLE {$table_prefix}mlm_bonus" );
	$wpdb->query( "TRUNCATE TABLE {$table_prefix}mlm_payout" );
	$wpdb->query( "TRUNCATE TABLE {$table_prefix}mlm_payout_master" );
	$wpdb->query( "TRUNCATE TABLE {$table_prefix}mlm_transaction" );
	$wpdb->query( "TRUNCATE TABLE {$table_prefix}mlm_epins");
	$wpdb->query( "TRUNCATE TABLE {$table_prefix}mlm_withdrawal");
        
	_e("<span class='msg'>All MLM data has been deleted from the system. You can now start a fresh by creating the First User of the network by <strong><a href='".admin_url()."admin.php?page=admin-settings&tab='>Clicking Here</a>.</strong>.</span>","unilevel-mlm-pro");
	
}

?>