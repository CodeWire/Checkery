<?php

wp_register_style( 'checkerybootstrap', plugins_url('css/bootstrap.min.css', __FILE__) );

wp_enqueue_style('checkerybootstrap');



$colorgroup = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_colorgroup");



$accurl = get_option('siteurl').'/wp-admin/admin.php?page=colorgroup';

//echo '<pre>';print_r($results);exit;

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
  <h2>Color Groups <a href="<?php echo $accurl; ?>&flag=add" class="btn btn-primary" style="float:right;">Add New Group</a></h2>
  <hr />
  <?php

if( $_GET['addnotice'] )

	echo '<div id="message" class="updated"><p><strong>Color Group has been added successfully</strong></p></div>';

	

if( $_GET['updatenotice'] )

	echo '<div id="message" class="error"><p><strong>Color Group has been updated successfully</strong></p></div>';

?>
<br />
  <table  cellspacing="0" width="90%">
    <thead>
      <tr>
        <th >Color Group Name</th>
        <th data-hide="tablet">Color</th>
        <th data-hide="phone,tablet">Status</th>
        <th data-hide="phone,tablet">Date Created</th>
        <th data-hide="phone,tablet">Action</th>
      </tr>
    </thead>
    <?php foreach($colorgroup as $color){ ?>
    <tbody>
    <td><?php echo $color->color_group; ?></td>
      <td><p style="color:<?php echo $color->color; ?>;"><span style="background:<?php echo $color->color; ?>;margin-right: 10px;padding: 5px 39px;">&nbsp;</span><?php echo $color->color; ?></p></td>
      <td><p class="field switch">
          <?php if($color->status == 1)

		{ 

			echo '<label for="radio1" class="cb-enable selected"><span>Enable</span></label>

		<label for="radio2" class="cb-disable"><span>Disable</span></label>';

		}

		else

		{

			echo '<label for="radio1" class="cb-enable "><span>Enable</span></label>

		<label for="radio2" class="cb-disable selected"><span>Disable</span></label>';

		}

		?>
        </p></td>
      <td><?php echo $color->date_created; ?></td>
      <td><?php 



echo ' <a href="'.$accurl.'&flag=edit&id='.$color->id.'" title="Edit Checklist Category

"><img src="'.plugins_url("images/edit.png", __FILE__).'" /></a>&nbsp;

		 <a onclick="return condelete();" href="'.$accurl.'&flag=delete&id='.$color->id.'" title="Delete"><img src="'.plugins_url("images/delete.png", __FILE__).'" /></a>'; ?>
      </td>
      </tbody>
      <?php } ?>
    <tfoot>
      <tr>
        <td colspan="9"><div class="pagination pagination-centered"></div></td>
      </tr>
    </tfoot>
  </table>
</div>
<script type="text/javascript">

	$= jQuery.noConflict(); 

    $(function () {

        $('table').footable();

	});
	
	function condelete()

{

	return confirm('Are you sure if you want to delete this item');

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

		

		var mapcolorkey=$("#map_color_key_"+kids).val();

		

		$.post("<?php echo get_option('siteurl'); ?>/wp-admin/admin-ajax.php", {action:"mapkey_info", "kval": encodeURIComponent(kval),"kid": encodeURIComponent(kids),"mapcolorkey": encodeURIComponent(mapcolorkey)}, function(str)	{

		

		alert('Updated Successfully');

});

		}

	

	}

	

</script>
