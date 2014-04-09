<?php
$filename = 'epin-export-' . date( 'YmdHis' ) . '.csv';
header("Content-type: text/csv");  
header("Cache-Control: no-store, no-cache");  
header('Content-Disposition: attachment; filename="'.$filename.'"');  
  
$outstream = fopen("php://output",'w');  
  
$values = $_POST['listarray'];
$test_data =   unserialize($values);
foreach( $test_data as $row )  
{  
    fputcsv($outstream, $row, ',', '"');  
}  
  
fclose($outstream); 
?>