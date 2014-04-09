<?php


function mlm_my_child_member_details_page()
{    

$table_prefix = mlm_core_get_table_prefix();
	$error = '';
	global $current_user;
	get_currentuserinfo();
	$sponsor_name = $current_user->user_login;
		
	if(!empty($_GET['lvl']) && $_GET['lvl']!='') {
	$level=$_GET['lvl'];
	}
	
		$res = mysql_fetch_array(mysql_query("SELECT user_key FROM {$table_prefix}mlm_users WHERE username = '".$sponsor_name."'"));
		
	$level_data=getLevelInfo($res['user_key'],$level);

?> 
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load('visualization', '1', {packages: ['table']});
</script>
<script type="text/javascript">
var visualization;
var data;
var options = {'showRowNumber': true};
function drawVisualization() {
  // Create and populate the data table.
  var dataAsJson =
  {cols:[
	{id:'A',label:'<?= _e("Username","unilevel-mlm-pro"); ?>',type:'string'},
	{id:'B',label:'<?= _e("First Name","unilevel-mlm-pro"); ?>',type:'string'},
	{id:'C',label:'<?= _e("Last Name","unilevel-mlm-pro"); ?>',type:'string'},
    {id:'D',label:'<?= _e("Sponsor","unilevel-mlm-pro"); ?>',type:'string'},
	{id:'E',label:'<?= _e("Email","unilevel-mlm-pro"); ?>',type:'string'},
    {id:'F',label:'<?= _e("Status","unilevel-mlm-pro"); ?>',type:'string'}],
  rows:[
  <?php for($i=0;$i<count($level_data);$i++) {  
			
	         ?>
                        {c:[{v:'<?=$level_data[$i]['username']?>'},
                        {v:'<?=$level_data[$i]['first_name']?>'},
                        {v:'<?=$level_data[$i]['last_name'] ?>'},
                        {v:'<?=$level_data[$i]['sponsor']?>'},
						{v:'<?=$level_data[$i]['email']?>'},
                        {v:'<?=$level_data[$i]['status']?>'}]},
  <?php } ?>
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
        	<div class="va-headname"><strong><?php _e('My Level '.$level.' Members','unilevel-mlm-pro');?></strong></div>
            <!--/va-headname-->
			<div class="va-admin-leg-details">
            	<!--va-admin-mid-->
				<div class="paging">
				  <form action="">
					<div class="left-side" style="width:30%;float:left;">
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
					<?php _e('Total Records','unilevel-mlm-pro');?>: <strong><?= count($level_data); ?></strong>
					</div>
					<div class="va-clear"></div>
				  </div>
				<div id="table"></div>
				<div class="va-clear"></div>
			</div>		
		</div>
	</div>	
		
<?php 
	} ?>