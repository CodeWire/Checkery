<?php

wp_register_style( 'checkerybootstrap', plugins_url('css/bootstrap.min.css', __FILE__) ); 

wp_enqueue_style('checkerybootstrap');	



$categories = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_checklist WHERE status ='1'");

?>
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
<h2>Import Checklist</h2>
<hr />
<?php

if( $_POST['notice'] )

	echo '<div id="message" class="updated"><p><strong>' . $_POST['notice'] . '</strong></p></div>';

	

if( $_POST['errornotice'] )

	echo '<div id="message" class="error"><p><strong>' . $_POST['errornotice'] . '</strong></p></div>';

?>
<form action="" method="post" enctype="multipart/form-data">
  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row"><label for="category">Checklist Name</label></th>
        <td><!--<select name="checklist_id" id="checklist_id">
            <option value="0">-- Choose Checklist Name -- </option>
			
            <?php 

foreach($categories as $category)

{ 

	echo '<option  value="'.$category->id.'" >'.$category->checklist.'</option>';

}

?>
          </select>-->
		  <input type="text" name="check_list_name"  />
		  </td>
      </tr>
      <tr>
        <th scope="row"><label for="blogname">Checklist CSV :</label></th>
        <td><input type="file" name="csv_import" size="31" id="csv_import"  class="full-width">
          <!--<a href="<?php //echo plugins_url("uploadedXLfile/sample.xls", __FILE__);?>">Sample Excel File</a>-->
        </td>
      </tr>
    </tbody>
  </table>
  <p class="submit">
    <input type="submit" value="Import" class="btn btn-primary" id="submit" name="submit">
    <input name="action" value="import_checklist" type="hidden" />
  </p>
</form>
