<?php
wp_register_style( 'checkerybootstrap', plugins_url('css/bootstrap.min.css', __FILE__) );
wp_enqueue_style('checkerybootstrap');	

if(isset($_GET['flag']) && $_GET['id'] != '')
{
	$categories = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_category WHERE checklist_id =".$_GET['id']);
	if(trim($_GET['flag']) == 'editoption')
	{
	
	$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_items WHERE id='".$_GET['itemid']."'");
	}
	$colorgroup = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_colorgroup");
	 
}

?>
<link href="<?php echo plugins_url("css/checkery.css", __FILE__);?>" rel="stylesheet" type="text/css"/>

<h2>Add New Checkbox Option</h2>
<hr />
<?php
if( $_POST['notice'] )
	echo '<div id="message" class="updated"><p><strong>' . $_POST['notice'] . '</strong></p></div>';
	
if( $_POST['errornotice'] )
	echo '<div id="message" class="error"><p><strong>' . $_POST['errornotice'] . '</strong></p></div>';

if(isset($_GET['id'])){ ?>
<form action="" method="post">
<table class="form-table">
<tbody>


<tr>
<th scope="row"><label for="check_item">Item Name</label></th>
<td><input type="text" class="regular-text" value="<?php echo ($results[0]->check_item != '') ? stripslashes($results[0]->check_item):'';?>" id="check_item" name="check_item" /></td>
</tr>
<tr>
<th scope="row"><label for="category">Category</label></th>
<td>
<select name="category" id="category">
<option value="0">-- Choose Category -- </option>
<?php 
foreach($categories as $category)
{ 
	if($category->id == $results[0]->category)
	{
		echo '<option selected="selected" value="'.$category->id.'" >'.$category->category.'</option>';
	}
	else
	{
		echo '<option  value="'.$category->id.'" >'.$category->category.'</option>';
	}
}
?>

</select></td>
</tr>
<tr>
<th scope="row"><label for="colorgroup">Color Group</label></th>
<td><select name="color_group">
<option value="0">-- Choose Color Group --</option>
<?php foreach($colorgroup as $color){ 

if($color->id == $results[0]->colorgroup)
	{
		echo '<option selected="selected" value="'.$color->id.'" >'.$color->color_group.'</option>';
	}
	else
	{
		echo '<option  value="'.$color->id.'" >'.$color->color_group.'</option>';
	}

} ?>
</select></td>
</tr>


<tr>
<th scope="row"><label for="blogname">Status</label></th>
<td>
<p class="field switch">
<?php
	if(trim($_GET['flag']) == 'addoption')
	{
		echo '<input type="radio" id="radio1" name="status" value="1"  checked />
		<input type="radio" id="radio2" name="status" value="0" />
		<label for="radio1" class="cb-enable selected"><span>Enable</span></label>
		<label for="radio2" class="cb-disable"><span>Disable</span></label>';
		$acctxt='add';
		$btntxt='Add';
		echo '<input type="hidden"  name="checklist_id" value="'.$_GET['id'].'"   />';
	}
	else
	{
		if($results[0]->status == 1)
		{
			echo '<input type="radio" id="radio1" name="status" value="1"  checked />
		<input type="radio" id="radio2" name="status" value="0" />
		<label for="radio1" class="cb-enable selected"><span>Enable</span></label>
		<label for="radio2" class="cb-disable"><span>Disable</span></label>';
		}
		else
		{
			echo '<input type="radio" id="radio1" name="status" value="1"   />
		<input type="radio" id="radio2" name="status" checked value="0" />
		<label for="radio1" class="cb-enable "><span>Enable</span></label>
		<label for="radio2" class="cb-disable selected"><span>Disable</span></label>';
		}
		$acctxt='edit';
		$btntxt='Update';
		
		echo '<input type="hidden"  name="id" value="'.$results[0]->id.'"   />';
		echo '<input type="hidden"  name="checklist_id" value="'.$_GET['id'].'"   />';
		}
?>
		
	</p>
</td>
</tr>


</tbody>
</table>
<p class="submit"><input type="submit" value="<?php echo $btntxt;?>" class="btn btn-primary  active" id="submit" name="submit">
<input name="action" value="<?php echo $acctxt;?>_checkeryitem" type="hidden" /></p>
</form>
<?php } else { echo '<div id="message" class="updated"><p><strong>Checklist ID Emptys</strong></p></div>'; }?>
  <script>
    if (document.location.search.match(/type=embed/gi)) {
      window.parent.postMessage('resize', "*");
    }
  </script>
<script type="text/javascript">
$= jQuery.noConflict(); 
$(document).ready( function(){
	$(".cb-enable").click(function(){
		var parent = $(this).parents('.switch');
		$('.cb-disable',parent).removeClass('selected');
		$(this).addClass('selected');
		$('.checkbox',parent).attr('checked', true);
	});
	$(".cb-disable").click(function(){
		var parent = $(this).parents('.switch');
		$('.cb-enable',parent).removeClass('selected');
		$(this).addClass('selected');
		$('.checkbox',parent).attr('checked', false);
	});
});

function condelete()
{
	return confirm('Are you sure if you want to delete this item');
}
</script>



