<?php
include("../../../../wp-config.php");
global $table_prefix;
global $wpdb;
if(isset($_POST['wdel_id'])){
$user_id= $_POST['wdel_id'];
$id= $_POST['w_id'];
$sql = "DELETE  from {$table_prefix}mlm_withdrawal  WHERE `user_id` = '".$user_id."' AND `id`='".$id."'"; 
mysql_query($sql)or die(mysql_error());
}
?>