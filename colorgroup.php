<?php

wp_register_style( 'checkerybootstrap', plugins_url('css/bootstrap.min.css', __FILE__) );

wp_enqueue_style('checkerybootstrap');

if(isset($_GET['id'])){

$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_colorgroup WHERE id=".$_GET['id']);

}

$accurl = get_option('siteurl').'/wp-admin/admin.php?page=colorgroup';

?>

<link href="<?php echo plugins_url("css/checkery.css", __FILE__);?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo plugins_url("css/colorpicker.css", __FILE__);?>" rel="stylesheet" type="text/css"/>



<link href="<?php echo plugins_url("css/foo/footable.core.css?v=2-0-1", __FILE__);?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo plugins_url("css/foo/footable.standalone.css", __FILE__);?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo plugins_url("css/foo/footable-demos.css", __FILE__);?>" rel="stylesheet" type="text/css"/>

<script src="<?php echo plugins_url("js/foo/footable.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>



<style>

.color-picker {

   

    margin-left: 0;

    margin-top: 32px;

	}

</style>

<!--<script src="<?php //echo plugins_url("js/foo/footable.sort.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>

-->

<?php

if(count($results) > 10)

{

	echo '<script src="'.plugins_url("js/foo/footable.paginate.js?v=2-0-1", __FILE__).'" type="text/javascript"></script>';

}

?>

<div style="width: 98%;">

<h2>Add New Color Group <a href="<?php echo $accurl; ?>&flag=add" class="btn btn-primary" style="float:right;">Add New Group</a></h2>

<hr />



<form action="" method="post">

<table class="form-table">

<tbody>

<tr>

<th scope="row"><label for="color_group">Color Group Name</label></th>

<td><input type="text" class="regular-text" value="<?php echo ($results[0]->color_group != '') ? stripslashes($results[0]->color_group):'';?>" id="color_group" name="color_group" /></td>

</tr>



<tr>

<th scope="row"><label for="color">Choose Color</label></th>

<td>

<input type="hidden" class="call-picker regular-text" value="<?php echo ($results[0]->color != '') ? stripslashes($results[0]->color):'';?>" id="color_key" placeholder="#FFFFFF" name="color_key">

  <div class="color-holder call-picker" style="background-color:<?php echo ($results[0]->color != '') ? stripslashes($results[0]->color):'#FFFFFF';?>"></div>

<div style="display:none;" id="color-picker" class="color-picker"></div>







</td>

</tr>





<tr>

<th scope="row"><label for="blogname">Status</label></th>

<td>

<p class="field switch">

<?php

	if(trim($_GET['flag']) == 'add')

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

?>

		

	</p>

</td>

</tr>





</tbody>

</table>

<p class="submit"><input type="submit" value="<?php echo $btntxt;?>" class="btn btn-primary  active" id="submit" name="submit">

<input name="action" value="<?php echo $acctxt;?>_colorgroup" type="hidden" /></p>

</form>



</div>

<script type="text/javascript">

	$= jQuery.noConflict(); 

    $(function () {

        $('#table').footable();

	});

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

</script>	



<script type="text/javascript">

var colorList = [ '000000', '993300', '333300', '003300', '003366', '000066', '333399', '333333', 

'660000', 'FF6633', '666633', '336633', '336666', '0066FF', '666699', '666666', 'CC3333', 'FF9933', '99CC33', '669966', '66CCCC', '3366FF', '663366', '999999', 'CC66FF', 'FFCC33', 'FFFF66', '99FF66', '99CCCC', '66CCFF', '993366', 'CCCCCC', 'FF99CC', 'FFCC99', 'FFFF99', 'CCffCC', 'CCFFff', '99CCFF', 'CC99FF', 'FFFFFF' ];

var picker = $('#color-picker');



for (var i = 0; i < colorList.length; i++ ) {

picker.append('<li class="color-item" data-hex="' + '#' + colorList[i] + '" style="background-color:' + '#' + colorList[i] + ';"></li>');

}



$('body').click(function () {

picker.fadeOut();

});



$('.call-picker').click(function(event) {

event.stopPropagation();

picker.fadeIn();

picker.children('li').hover(function() {

var codeHex = $(this).data('hex');



$('.color-holder').css('background-color', codeHex);

$('#color_key').val(codeHex);

});

});

//@ sourceURL=pen.js

</script>



