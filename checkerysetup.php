<?php

wp_register_style( 'checkerybootstrap', plugins_url('css/bootstrap.min.css', __FILE__) ); ?>
<link href="<?php echo plugins_url("css/foo/footable.core.css?v=2-0-1", __FILE__);?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo plugins_url("css/foo/footable.standalone.css", __FILE__);?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo plugins_url("css/foo/footable-demos.css", __FILE__);?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo plugins_url("css/jquery-ui.css", __FILE__);?>" rel="stylesheet" type="text/css"/>

<script src="<?php echo plugins_url("js/foo/footable.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>
<script src="<?php echo plugins_url("js/foo/footable.sort.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>
<script src="<?php echo plugins_url("js/foo/footable.paginate.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>

<script src="<?php echo plugins_url("js/jquery-ui.js?v=2-0-1", __FILE__);?>" type="text/javascript"></script>


<?php wp_enqueue_style('checkerybootstrap');	



if(isset($_GET['flag']))

{

	if(trim($_GET['flag']) == 'edit')

	{

	

	$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_checklist WHERE id='".$_GET['id']."' ORDER BY id ASC");

//echo '<pre>';print_r($results);exit;

	}

}



?>

<style>
.custom-combobox {
position: relative;
display: inline-block;
}
.custom-combobox-toggle {
position: absolute;
top: 0;
bottom: 0;
margin-left: -1px;
padding: 0;
}
.custom-combobox-input {
margin: 0;
padding: 5px 10px;
}
</style>
<script>
var jq = jQuery.noConflict();
(function( jq ) {
jq.widget( "custom.combobox", {
_create: function() {
this.wrapper = jq( "<span>" )
.addClass( "custom-combobox" )
.insertAfter( this.element );
this.element.hide();
this._createAutocomplete();
this._createShowAllButton();
},
_createAutocomplete: function() {
var selected = this.element.children( ":selected" ),
value = selected.val() ? selected.text() : "";
this.input = jq( "<input>" )
.appendTo( this.wrapper )
.val( value )
.attr( "title", "" )
.addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
.autocomplete({
delay: 0,
minLength: 0,
source: jq.proxy( this, "_source" )
})
.tooltip({
tooltipClass: "ui-state-highlight"
});
this._on( this.input, {
autocompleteselect: function( event, ui ) {
ui.item.option.selected = true;
this._trigger( "select", event, {
item: ui.item.option
});
},
autocompletechange: "_removeIfInvalid"
});
},
_createShowAllButton: function() {
var input = this.input,
wasOpen = false;
jq( "<a>" )
.attr( "tabIndex", -1 )
.attr( "title", "Show All Items" )
.tooltip()
.appendTo( this.wrapper )
.button({
icons: {
primary: "ui-icon-triangle-1-s"
},
text: false
})
.removeClass( "ui-corner-all" )
.addClass( "custom-combobox-toggle ui-corner-right" )
.mousedown(function() {
wasOpen = input.autocomplete( "widget" ).is( ":visible" );
})
.click(function() {
input.focus();
// Close if already visible
if ( wasOpen ) {
return;
}
// Pass empty string as value to search for, displaying all results
input.autocomplete( "search", "" );
});
},
_source: function( request, response ) {
var matcher = new RegExp( jq.ui.autocomplete.escapeRegex(request.term), "i" );
response( this.element.children( "option" ).map(function() {
var text = jq( this ).text();
if ( this.value && ( !request.term || matcher.test(text) ) )
return {
label: text,
value: text,
option: this
};
}) );
},
_removeIfInvalid: function( event, ui ) {
// Selected an item, nothing to do
if ( ui.item ) {
return;
}
// Search for a match (case-insensitive)
var value = this.input.val(),
valueLowerCase = value.toLowerCase(),
valid = false;
this.element.children( "option" ).each(function() {
if ( jq( this ).text().toLowerCase() === valueLowerCase ) {
this.selected = valid = true;
return false;
}
});
// Found a match, nothing to do
if ( valid ) {
return;
}
// Remove invalid value
this.input
.val( "" )
.attr( "title", value + " didn't match any item" )
.tooltip( "open" );
this.element.val( "" );
this._delay(function() {
this.input.tooltip( "close" ).attr( "title", "" );
}, 2500 );
this.input.autocomplete( "instance" ).term = "";
},
_destroy: function() {
this.wrapper.remove();
this.element.show();
}
});
})( jQuery );
jq(function() {
jq( "#combobox" ).combobox();
jq( "#toggle" ).click(function() {
jq( "#combobox" ).toggle();
});
});
</script>
<style>

.wp-media-buttons .insert-media, .wp-editor-tabs

{

	display:none !important;

}

#poststuff,#title

{

	

}.field.switch > span {

    display: block;

    float: left;

    line-height: 30px;

    padding: 0 20px;

	font-weight:bold;

}

#description_ifr

{

	

    height: 176px !important;

    width: 100% !important;

}



</style>
<link href="<?php echo plugins_url("css/checkery.css", __FILE__);?>" rel="stylesheet" type="text/css"/>
<h2><?php echo ($results[0]->checklist != '') ? 'Update '.stripslashes($results[0]->checklist):'Add New Checklist';?></h2>
<hr />
<?php

if(!isset($_POST['notice'])) {
if( isset($_GET['addsuccess']) )
{
	echo '<div id="message" class="updated"><p><strong>Checklist has been added successfully</strong></p></div>';
}
}
	
if( $_POST['notice'] )
echo '<div id="message" class="updated"><p><strong>'.$_POST['notice'].'</strong></p></div>';

if( $_POST['errornotice'] )

	echo '<div id="message" class="error"><p><strong>' . $_POST['errornotice'] . '</strong></p></div>';

?>
<form action="" method="post">
  <table class="form-table">
    <tbody>
      <tr>
        <td  id="titlediv"><input type="text" class="regular-text" value="<?php echo ($results[0]->checklist != '') ? stripslashes($results[0]->checklist):'';?>" id="title" name="checklist" placeholder="Name " /></td>
        <td><p s class="field"><span> Display Name </span>
            <?php if(trim($_GET['flag']) == 'add')
		{ ?>
            <input type="checkbox" name="show_title" value="1"  />
            <?php }
		else{ ?>
            <input type="checkbox" name="show_title" value="1"  <?php if($results[0]->show_title == 1) { ?> checked="checked" <?php  } ?> />
            <?php }
		?>
          </p></td>
        <td><p style="float:right; padding-right:10px;" class="field switch"><span> Show Color Legend </span>
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

		

		echo '<input type="hidden"  name="cat_id" value="'.$results[0]->id.'"   />';

		}

?>
          </p></td>
      </tr>
      <tr>
        <td colspan="3"><div id="poststuff">
            <?php 

			if($results[0]->description != '')

			{

				the_editor($results[0]->description,'description');

			}

			else

			{

				the_editor('','description');

			}

			 ?>
          </div></td>
      </tr>
    </tbody>
  </table>
 
  <p><span><strong>Select font-family for Category Title</strong></span>&nbsp;&nbsp;
  <?php  
  $checkeryobj = new checkery();
  $fonts = $checkeryobj->checkry_googlefont_list(); ?> 
  <select name="category_font" id="combobox">
  <option value="" style="width:400px; height:20px;">Select Font-Family</option>
  <?php 
   
   foreach($fonts as $font){ $font_img = str_replace(" ","",$font); $font_img =strtolower($font_img);
     $src = CHECKERY_PLUGIN_URL.'styles-fonts/png/'.$font_img.'-regular.png';
	 $selected= ''; 
	 if($results[0]->category_font ==  $font) { $selected = 'selected="selected"'; } ?>
   <option style="background: url('<?php echo $src; ?>') no-repeat left center #000; width:400px; height:50px; cursor:pointer; font-size:0;"  <?php echo $selected; ?> value="<?php echo $font; ?>" ><span><img src="<?php echo $src; ?>"  /><?php echo $font; ?></span></option>
  
  <?php }
  ?> </select>
  </p>
  <p style="margin:6px 0;"><img src="<?php echo plugins_url("images/checkerylogo.png", __FILE__); ?>" />
  <p class="submit">
    <input type="submit" value="<?php echo $btntxt;?>" class="btn btn-primary  active" id="submit" name="submit">
    <input name="action" value="<?php echo $acctxt;?>_checklist" type="hidden" />
  </p>
</form>
<?php if($results[0]->id != ''){
$category_details=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_category WHERE checklist_id='".$_GET['id']."'");
?>
<p class="submit addnewoption" style="text-align:right; padding-right:20px;"> <a class="btn btn-primary" href="<?php echo get_option('siteurl').'/wp-admin/admin.php?page=checkery&flag=categoryadd&id='.$results[0]->id;?>">Add New Category</a> &nbsp;<?php if(count($category_details)>0){?> <a class="btn btn-primary" href="<?php echo get_option('siteurl').'/wp-admin/admin.php?page=checkery&flag=addoption&id='.$results[0]->id;?>">Add New Option</a> <?php } ?></p>
<?php if(count($category_details)>0){?>
<table  id="categorytable" cellspacing="0" width="90%">
  <thead>
    <tr>
      <th>Category name</th>
      <th>Category Items</th>
      <th  data-hide="phone,tablet">Category Font-Family</th>
      <th data-hide="phone,tablet">Status</th>
      <th data-hide="phone,tablet">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($category_details as $category_detail){
$category_items=$wpdb->get_results("SELECT id,check_item FROM ".$wpdb->prefix."checkery_items WHERE category=".$category_detail->id);
$cat_items = array();
foreach($category_items as $category_item){
$cat_items[] = '<a href="'.$accurl.'?page=checkery&flag=editoption&itemid='.$category_item->id.'&id='.$category_detail->checklist_id.'">'.$category_item->check_item.'</a>';
} 
$cat_items = implode(",",$cat_items);
?>
    <tr>
      <td><?php echo '<a href="'.$accurl.'?page=checkery&flag=editcatoption&category_id='.$category_detail->id.'&id='.$category_detail->checklist_id.'" title="Edit Checklist Category">'.$category_detail->category.'</a>'; ?></td>
      <td><?php echo $cat_items; ?></td>
      <td><?php echo $category_detail->category_font; ?></td>
      <td><p class="field switch">
          <?php if($category_detail->status == 1)
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
      <td><?php echo ' <a href="'.$accurl.'?page=checkery&flag=editcatoption&category_id='.$category_detail->id.'&id='.$category_detail->checklist_id.'" title="Edit Checklist Category"><img src="'.plugins_url("images/edit.png", __FILE__).'" /></a>&nbsp;
		 <a onclick="return condelete();" href="'.$accurl.'?page=checkery&flag=deletecatoption&category_id='.$category_detail->id.'&id='.$category_detail->checklist_id.'" title="Delete"><img src="'.plugins_url("images/delete.png", __FILE__).'" /></a>'; ?> </td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<?php /*?><table  id="table" cellspacing="0" width="90%">

<thead>

<tr>

<th>Option name</th>

<th  data-hide="phone,tablet">Category</th>

<th data-hide="phone,tablet">Status</th>

<th data-hide="phone,tablet">Date Created</th>

<th data-hide="phone,tablet">Action</th>

</tr>

</thead>
<?php 

$category_items=$wpdb->
get_results("SELECT * FROM ".$wpdb->prefix."checkery_items  WHERE checklist_id='".$_GET['id']."'"); 
$accurl = get_option('siteurl').'/wp-admin/admin.php?page=checkery';
foreach($category_items as $items)
{ 
$cat_items=$wpdb->get_results("SELECT category FROM ".$wpdb->prefix."checkery_category WHERE  id='".$items->category."'"); 
?>
<tbody>
<td><a href="<?php echo $accurl.'&flag=edit&itemid='.$items->id.'&id='.$items->checklist_id; ?>"><?php echo $items->check_item; ?></td>
<td><?php echo $cat_items[0]->category; ?></td>
<td><p class="field switch">
    <?php if($items->status == 1)

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
<td><?php echo $items->date_created; ?></td>
<td><?php echo ' <a href="'.$accurl.'&flag=editoption&itemid='.$items->id.'&id='.$items->checklist_id.'" title="Edit Checklist Category

"><img src="'.plugins_url("images/edit.png", __FILE__).'" /></a>&nbsp;

		 <a onclick="return condelete();" href="'.$accurl.'&flag=deleteoption&itemid='.$items->id.'&id='.$items->checklist_id.'" title="Delete"><img src="'.plugins_url("images/delete.png", __FILE__).'" /></a>'; ?> </td>
</tbody>
<?php } ?>
<tfoot>
  <tr>
    <td colspan="9"><div class="pagination pagination-centered"></div></td>
  </tr>
</tfoot>
</table><?php */?>

<?php }} ?>
<script type="text/javascript">

$= jQuery.noConflict(); 

$(document).ready( function(){

	 $(function () {

        $('#table').footable();
    });
	 $(function () {

        $('#categorytable').footable();
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



function condelete()

{

	return confirm('Are you sure if you want to delete this item');

}

</script>
