<link href="<?php echo plugins_url("css/foo/footable.core.css?v=2-0-1", __FILE__);?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo plugins_url("css/foo/footable.standalone.css", __FILE__);?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo plugins_url("css/foo/footable-demos.css", __FILE__);?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo plugins_url("js/foo/footable.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>
<script src="<?php echo plugins_url("js/foo/footable.sort.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>
<script src="<?php echo plugins_url("js/foo/footable.paginate.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>
<?php

wp_register_style( 'checkerybootstrap', plugins_url('css/bootstrap.min.css', __FILE__) );



wp_enqueue_style('checkerybootstrap');	
if(isset($_GET['category_id'])){
$results=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_category WHERE id=".$_GET['category_id']);
}

?>
<link href="<?php echo plugins_url("css/checkery.css", __FILE__);?>" rel="stylesheet" type="text/css"/>
<?php $title = 'Add Category'; if(isset($_GET['category_id'])){  $title = 'Update '.$results[0]->category;} ?>
<h2><?php echo $title; ?></h2>
<hr />
<?php

if( $_POST['notice'] )

	echo '<div id="message" class="updated"><p><strong>' . $_POST['notice'] . '</strong></p></div>';

	

if( $_POST['errornotice'] )

	echo '<div id="message" class="error"><p><strong>' . $_POST['errornotice'] . '</strong></p></div>';

?>
<form action="" method="post">
  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row"><label for="blogname">Category Name </label></th>
        <td><input type="text" class="regular-text" value="<?php echo ($results[0]->category != '') ? stripslashes($results[0]->category):'';?>" id="category" name="category" /></td>
      </tr>
      <tr style="visibility:hidden">
        <th style="padding:0;">Select font-family for Category</th>
        <td style="padding:0;"><select name="category_font">
            <option value="">Select Font-family</option>
            <?php 
		   $checkeryobj = new checkery();
		  $fonts = $checkeryobj->checkry_googlefont_list();
		   foreach($fonts as $font){ ?>
            <?php $selected= ''; if(str_replace("_"," ",$results[0]->category_font) ==  $font) { $selected = 'selected="selected"'; } ?>
            <option <?php echo $selected; ?> value="<?php echo $font; ?>"><?php echo $font; ?></option>
            <?php }
  ?>
          </select>
        </td>
      </tr>
      </tr>
      
      <tr>
        <th scope="row"><label for="blogname">status</label></th>
        <td><p class="field switch">
            <?php

	if(trim($_GET['flag']) == 'categoryadd')

	{

		echo '<input type="radio" id="radio1" name="status" value="1"  checked />

		<input type="radio" id="radio2" name="status" value="0" />

		<label for="radio1" class="cb-enable selected"><span>Enable</span></label>

		<label for="radio2" class="cb-disable"><span>Disable</span></label>';

		$acctxt='add';

		$btntxt='Add';

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

		}

		

		echo '<input type="hidden"  name="checklist_id" value="'.$_GET['id'].'"   />';

?>
          </p></td>
      </tr>
    </tbody>
  </table>
  <p class="submit">
    <input type="submit" value="<?php echo $btntxt;?>" class="btn btn-primary  active" id="submit" name="submit">
    <input name="action" value="<?php echo $acctxt;?>_category" type="hidden" />
  </p>
</form>
<div style="clear:both;"></div>
<?php if(isset($_GET['category_id'])){?>
<p class="submit addnewoption" style="text-align:right; padding-right:20px;">

		<a class="btn btn-primary" href="<?php echo get_option('siteurl').'/wp-admin/admin.php?page=checkery&flag=addoption&id='.$_GET['id'];?>">Add New Option</a>
</p>
<?php $category_items=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_items WHERE category=".$_GET['category_id']);
?>
<table  id="categoryitemtable" cellspacing="0" width="90%">
  <thead>
    <tr>
      <th>Category Items</th>
	  <th>Color Group</th>
      <th data-hide="phone,tablet">Status</th>
      <th data-hide="phone,tablet">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($category_items as $category_item){
?>
    <tr>
      <td><?php echo '<a href="'.$accurl.'?page=checkery&flag=editoption&itemid='.$category_item->id.'&id='.$category_item->checklist_id.'" title="Edit Checklist Item">'.$category_item->check_item.'</a>'; ?></td>
	  <?php $color_group = '-';
	  $color = '#000000';
	  if($category_item->colorgroup != 0) {
	  	$color_groups=$wpdb->get_results("SELECT color_group,color FROM ".$wpdb->prefix."checkery_colorgroup WHERE id=".$category_item->colorgroup);
		$color_group = $color_groups[0]->color_group;
		$color =  $color_groups[0]->color;

	  }
	   ?>
	  <td><p style=" font-size:15px; color:<?php echo $color; ?>"><?php echo $color_group; ?></p></td>
      <td><p class="field switch">
          <?php if($category_item->status == 1)
	{ 
		echo '<label for="radio1" class="cb-enable selected"><span>Enable</span></label>
	<label for="radio2" class="cb-disable"><span>Disable</span></label>';
	}
	else
	{
		echo '<label for="radio1" class="cb-enable"><span>Enable</span></label>
	<label for="radio2" class="cb-disable selected"><span>Disable</span></label>';
	}
	?>
        </p></td>
      <td><?php echo ' <a href="'.$accurl.'?page=checkery&flag=editoption&itemid='.$category_item->id.'&id='.$category_item->checklist_id.'" title="Edit Checklist Category"><img src="'.plugins_url("images/edit.png", __FILE__).'" /></a>&nbsp;
		 <a onclick="return condelete();" href="'.$accurl.'?page=checkery&flag=deleteoption&itemid='.$category_item->id.'&id='.$category_item->checklist_id.'" title="Delete"><img src="'.plugins_url("images/delete.png", __FILE__).'" /></a>'; ?> </td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<?php } ?>
<script type="text/javascript">

    if (document.location.search.match(/type=embed/gi)) {

      window.parent.postMessage('resize', "*");

    }

  </script>
<script type="text/javascript">

$= jQuery.noConflict(); 

$(document).ready( function(){

$(function () {

        $('#categoryitemtable').footable();
    });

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

</script>
