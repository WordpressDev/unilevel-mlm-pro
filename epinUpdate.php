<?php
require_once('../../../wp-config.php');
global $wpdb;
$table_prefix = mlm_core_get_table_prefix();

extract($_REQUEST);
$user_key=getuserkeybyid($user_id);
if(!empty($epin))
{

$sql = "SELECT * FROM {$table_prefix}mlm_epins WHERE epin_no='".$epin."' AND status=1";
$results = $wpdb->get_results($sql);
if($wpdb->num_rows!=1)
{

    $sql = "update {$table_prefix}mlm_epins set user_key='{$user_key}', date_used=now(), status=1 where epin_no ='{$epin}' ";
    $epinUpdate=$wpdb->query($sql);
    $userUpdate=mlmUserUpdateePin($user_id,$epin);
    
}	
	
	if($epinUpdate && $userUpdate)
    {
	
	$pointResult = mysql_query("select point_status from {$table_prefix}mlm_epins where epin_no = '{$epin}'");
	$pointStatus = mysql_fetch_row($pointResult);
	// to epin point status 1 
	if($pointStatus[0]=='1')
	{	
	UserStatusUpdate($user_id);
	}
	
	
        _e("<span class='error' style='color:green'>ePin Update.</span>");
    }
    else
    {
        _e("<span class='error' style='color:red'>ePin Not update.</span>");
    }
}





?>