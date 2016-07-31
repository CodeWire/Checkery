<?php
wp_register_style( 'checkerybootstrap', plugins_url('css/bootstrap.min.css', __FILE__) );
wp_enqueue_style('checkerybootstrap');
$addsql='';
if(isset($_GET['maincat']))
{
	
	$addsql=" WHERE main_cat_id='".intval($_GET['maincat'])."'";
	
}

$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_subcategory ".$addsql." ORDER BY sub_id ASC");

$accurl = get_option('siteurl').'/wp-admin/admin.php?page=checkerylist';
//echo '<pre>';print_r($results);exit;
?>
<link href="<?php echo plugins_url("css/checkery.css", __FILE__);?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo plugins_url("css/colorpicker.css", __FILE__);?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo plugins_url("css/foo/footable.core.css?v=2-0-1", __FILE__);?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo plugins_url("css/foo/footable.standalone.css", __FILE__);?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo plugins_url("css/foo/footable-demos.css", __FILE__);?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo plugins_url("js/foo/footable.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>

<!--<script src="<?php //echo plugins_url("js/foo/footable.sort.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>
-->
<?php
if(count($results) > 10)
{
	echo '<script src="'.plugins_url("js/foo/footable.paginate.js?v=2-0-1", __FILE__).'" type="text/javascript"></script>';
}
?>
<div style="width: 98%;">
<h2><a href="<?php echo $accurl.'&flag=add';?>" class="btn btn-primary active" role="button">Add Items To Checklist</a></h2>
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
<th ><!--<input name="checkbox" id="checkbox" onclick="javascript:RCheckAll();" type="checkbox" />-->S.No</th>
<th data-hide="tablet">Checklist Name</th>
<th>Items on Checklist</th>
<th data-hide="phone,tablet">Status</th>
<th data-hide="phone,tablet">Color</th>
<th data-hide="phone,tablet">Action</th>
</tr>
</thead>
<tbody>
<?php 
if(count($results) > 0)
{


$i=1;
	foreach($results as $catdata)
	{
	
	$maincat= $wpdb->get_results("SELECT ID,category_name FROM ".$wpdb->prefix."checkery_category WHERE ID='".$catdata->main_cat_id."'");
	$color_key=($catdata->color_key != '') ? stripslashes($catdata->color_key):'';
//<input type="checkbox" name="sub_cat[]" value="'.$catdata->sub_id.'" />
		echo ' <tr>
		<td>'.$i.'</td>
		<td>'.stripslashes($maincat[0]->category_name).'</td>
		<td>'.stripslashes($catdata->sub_cat_name).'</td>
		<td class="txtright" ><p class="field switch">';
		if($catdata->status == 1)
		{
			echo '<label for="radio1" class="cb-enable selected"><span>Enable</span></label>
		<label for="radio2" class="cb-disable"><span>Disable</span></label>';
		}
		else
		{
			echo '<label for="radio1" class="cb-enable "><span>Enable</span></label>
		<label for="radio2" class="cb-disable selected"><span>Disable</span></label>';
		}
		echo '</p></td>
		<td class="txtright" ><input type="text" class="call-picker regular-text" value="'.$color_key.'" id="map_color_key_'.$catdata->sub_id.'"  name="map_color_key">
  <div class="color-holder call-picker" id="color-holder-'.$catdata->sub_id.'" onclick="call_picker('.$catdata->sub_id.')" style="background-color:'.$color_key.'"></div>
  <div style="display:none;" id="color-picker-'.$catdata->sub_id.'" class="color-picker"></div></td>
		 <td><a href="'.$accurl.'&flag=edit&id='.$catdata->sub_id.'"  title="Edit Checklist Item"><img src="'.plugins_url("images/edit.png", __FILE__).'" /></a>&nbsp;<a onclick="return condelete();" href="'.$accurl.'&flag=delete&id='.$catdata->sub_id.'" title="Delete"><img src="'.plugins_url("images/delete.png", __FILE__).'" /></a></td>
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
	
	function RCheckAll()
	{
		if(document.getElementById("checkbox").checked == true)
		{
			chk=document.getElementsByName("sub_cat[]");
			for(var i=0;i<chk.length;i++)
			{
				chk[i].checked = true;
			}
		}
		else
		{
			chk=document.getElementsByName("sub_cat[]");
			for(var i=0;i<chk.length;i++)
			{
				chk[i].checked = false;
			}
		} 
	}
	

function condelete()
{
	var con=confirm("Are you sure if you want to delete this category.");
	return con;
}
</script>

<script type="text/javascript">
var colorList = [ '000000', '993300', '333300', '003300', '003366', '000066', '333399', '333333', 
'660000', 'FF6633', '666633', '336633', '336666', '0066FF', '666699', '666666', 'CC3333', 'FF9933', '99CC33', '669966', '66CCCC', '3366FF', '663366', '999999', 'CC66FF', 'FFCC33', 'FFFF66', '99FF66', '99CCCC', '66CCFF', '993366', 'CCCCCC', 'FF99CC', 'FFCC99', 'FFFF99', 'CCffCC', 'CCFFff', '99CCFF', 'CC99FF', 'FFFFFF' ];
var picker = $('.color-picker');

for (var i = 0; i < colorList.length; i++ ) {
picker.append('<li  class="color-item" data-hex="' + '#' + colorList[i] + '" style="background-color:' + '#' + colorList[i] + ';"></li>');
}

/*$('body').click(function () {
picker.fadeOut();
});*/



/*$('.call-picker').click(function(event) {
event.stopPropagation();
picker.fadeIn();
picker.children('li').hover(function() {
var codeHex = $(this).data('hex');

$('.color-holder').css('background-color', codeHex);
$('#color_key').val(codeHex);
});
});*/

function call_picker(pickid)
{


$('.color-picker').fadeOut();

	var picker = $('#color-picker-'+pickid);
	
	picker.fadeIn();
	picker.children('li').hover(function() {
	var codeHex = $(this).data('hex');
	
	$(this).attr('onclick','closepicker('+pickid+');');
	
	$('#color-holder-'+pickid).css('background-color', codeHex);
	$('#map_color_key_'+pickid).val(codeHex);
	//updatemapkey(pickid);
	});

	
}
function closepicker(lid)
{
	$('.color-picker').fadeOut();
	updatemapkey(lid);
}

//@ sourceURL=pen.js
    
	function updatemapkey(kids)
	{
		if(kids != '')
		{
		
		var kval=$("#map_name_"+kids).val();
		
		var colorkey=$("#map_color_key_"+kids).val();
		
		$.post("<?php echo get_option('siteurl'); ?>/wp-admin/admin-ajax.php", {action:"mapkey_info", "kval": encodeURIComponent(kval),"kid": encodeURIComponent(kids),"colorkey": encodeURIComponent(colorkey)}, function(str)	{
		
		alert('Updated Successfully');
});
		}
	
	}
	
</script>