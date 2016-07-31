<?php
wp_register_style( 'checkerybootstrap', plugins_url('css/bootstrap.min.css', __FILE__) );
wp_enqueue_style('checkerybootstrap');
$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_track ORDER BY ID DESC");
$accurl = get_option('siteurl').'/wp-admin/admin.php?page=checkery';
//echo '<pre>';print_r($results);exit;
?>
<link href="<?php echo plugins_url("css/checkery.css", __FILE__);?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo plugins_url("css/foo/footable.core.css?v=2-0-1", __FILE__);?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo plugins_url("css/foo/footable.standalone.css", __FILE__);?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo plugins_url("css/foo/footable-demos.css", __FILE__);?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo plugins_url("js/foo/footable.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>
<script src="<?php echo plugins_url("js/foo/footable.sort.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>
<!--<script src="<?php //echo plugins_url("js/foo/footable.paginate.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>
--><div style="width: 98%;">
<h2>Track Checklist</a></h2>
<hr />

<table  cellspacing="0" width="90%">
<thead>
<tr>
<th >S.No</th>
<th>Checklist Name</th>
<th data-hide="phone,tablet">IP Address</th>
<th data-hide="phone,tablet">Date</th>
<th data-hide="phone,tablet">Details</th>
</tr>
</thead>
<tbody>
<?php 
if(count($results) > 0)
{
	  

$i=1;
	foreach($results as $catdata)
	{
	
$maincat= $wpdb->get_results("SELECT ID,checklist FROM ".$wpdb->prefix."checkery_checklist WHERE ID='".$catdata->main_cat_id."'");
	
	$accurl = get_option('siteurl').'/wp-admin/admin.php?page=checkery&flag=edit';
	
		echo ' <tr>
		<td>'.$i.'</td>
		<td><a target="_blank" href="'.$accurl.'&id='.$maincat[0]->ID.'">'.stripslashes($maincat[0]->checklist).'</a></td>
		
		
		<td class="txtright" >'.$catdata->ip_address.'</td>
		<td class="txtright" >'.$catdata->date_time.'</td>
		 <td>
		  <a href="'.$catdata->page_url.'" target="_blank" title="Details">Details</a>
		 </td>
		</tr>';
		$i++;
	
	}

}
?></tbody>

<tfoot>
<tr>
<td colspan="9"><div class="pagination pagination-centered"></div>
</td>
</tr>
</tfoot>
</table></div>
<script type="text/javascript">
	$= jQuery.noConflict(); 
    $(function () {
        $('table').footable();
		
	

    });
	
	
	

function condelete()
{
	var con=confirm("Are you sure if you want to delete this category.");
	return con;
}
</script>