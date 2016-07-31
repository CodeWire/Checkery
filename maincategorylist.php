<?php
wp_register_style( 'checkerybootstrap', plugins_url('css/bootstrap.min.css', __FILE__) );
wp_enqueue_style('checkerybootstrap');
$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_checklist ORDER BY id ASC");
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
<div class="checkery_logo"><img src="<?php echo plugins_url("images/checkerylogo.png", __FILE__); ?>" /></div>
<h2><a  href="<?php echo $accurl.'&flag=add';?>" class="btn btn-primary active" role="button">Add New Checklist</a>
<div style="float:right"><a  href="<?php echo $accurl.'&flag=importcsv';?>" class="btn btn-primary active" role="button">Import CSV Checklist</a></div>
</h2>
<hr />
<?php
if( $_POST['notice'] )
	echo '<div id="message" class="updated"><p><strong>' . $_POST['notice'] . '</strong></p></div>';
	
if( $_POST['errornotice'] )
	echo '<div id="message" class="error"><p><strong>' . $_POST['errornotice'] . '</strong></p></div>';
?>
<table  cellspacing="0" width="90%">
<thead>
<tr>
<th nowrap="nowrap">Checklist name</th>
<th nowrap="nowrap" data-hide="phone,tablet">Shortcode</th>
<th data-hide="phone,tablet">Category</th>
<th data-hide="phone,tablet">Item On Checklist</th>
<th nowrap="nowrap" data-hide="phone,tablet">Status</th>
<th nowrap="nowrap" data-hide="phone,tablet">Date Created</th>
<th nowrap="nowrap" data-hide="phone,tablet">Action</th>
</tr>
</thead>
<tbody>
<?php 
if(count($results) > 0)
{ 
$subcaturl = get_option('siteurl').'/wp-admin/admin.php?page=checkery';

	foreach($results as $checklistdata)
	{
		echo ' <tr><td  width="10%"  nowrap="nowrap"> <a href="'.$subcaturl.'&flag=edit&id='.$checklistdata->id.'">'.stripslashes($checklistdata->checklist).'</a></td>
		
		<td nowrap="nowrap" width="10%" ><input type="text" value="[checklist id='.$checklistdata->id.']" /></td>';
		$categories = $wpdb->get_results("SELECT category FROM ".$wpdb->prefix."checkery_category WHERE checklist_id=".$checklistdata->id);
		$category = array();
		foreach($categories as $key => $value){
			$category[] = $value->category;
		}
		
		echo '<td  width="20%">'.implode(', ',$category).'</td>';
		
		$category_items = $wpdb->get_results("SELECT check_item FROM ".$wpdb->prefix."checkery_items WHERE checklist_id=".$checklistdata->id);
		$items = array();
		foreach($category_items as $key => $value){
			$items[] = $value->check_item;
		}
		
		
		echo '<td  width="25%">'.implode(', ',$items).'</td>';
		
		
		echo '<td  width="16%" class="txtright" nowrap="nowrap"><p class="field switch">';
		if($checklistdata->status == 1)
		{
			echo '<label for="radio1" class="cb-enable selected"><span style="padding:0 5px;">Enable</span></label>
		<label for="radio2" class="cb-disable"><span style="padding:0 5px;">Disable</span></label>';
		}
		else
		{
			echo '<label for="radio1" class="cb-enable "><span style="padding:0 5px;">Enable</span></label>
		<label for="radio2" class="cb-disable selected"><span style="padding:0 5px;">Disable</span></label>';
		}
		echo '</p></td>
		<td  width="10%" class="txtright" nowrap="nowrap">'.$checklistdata->date_created.'</td>
		 <td  width="10%" >		 
		  <a href="'.$accurl.'&flag=edit&id='.$checklistdata->id.'" title="Edit Checklist Category
"><img src="'.plugins_url("images/edit.png", __FILE__).'" /></a>&nbsp;
		 <a onclick="return condelete();" href="'.$accurl.'&flag=delete&id='.$checklistdata->id.'" title="Delete"><img src="'.plugins_url("images/delete.png", __FILE__).'" /></a>
		 </td>
		</tr>';
	
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