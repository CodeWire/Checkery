<?php if($category_font != ''){  ?>
 <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo str_replace("_"," ",$category_font); ?>"  />
<style>
      .clsCategory_name {
        font-family: '<?php echo str_replace("_"," ",$category_font); ?>', serif;
        font-size: 48px;
      }
    </style>
<?php } ?>
<?php
wp_register_style( 'checkerycomponent', plugins_url('css/checkery_component.css', __FILE__) );

wp_enqueue_style('checkerycomponent');

echo '<div id="checkerycomponent">';

/*<h1>'.stripslashes($catdata[0]->category_name).'</h1>

<div>'.stripslashes($catdata[0]->category_desc).'</div>*/

$main_cat_id=1;

if($show_title == 1){
echo '<h1 class="clschecklist_title">'.$checklist_title.'</h1>';
}
echo '<p>'.$checklist_description.'</p>

<div><form class="me-select">
<h2>Legend</h2>
<ul id="me-select-title">';

	foreach($colorgrouparray as $subkey=>$subitems)

	{
	if($subitems != '')

		{
			echo '<li >';

			echo '<div style="color: '.$subitems.'";><span  style="color: '.$subitems.';font-weight:bold;">'.ucfirst(stripslashes($subkey)).'</span><span style="display:none;">&nbsp;</span></div>';
			echo '</li>';

		}
	}

echo '</ul></form></div>

<form class="me-select me-listitems">';

	foreach($checklist_items as $key => $subitems)

	{
	
		if($subitems['ff'] != ''){ ?>
		 <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo str_replace("_"," ",$subitems['ff']); ?>"  />
		<style>
			  .<?php echo str_replace(" ","_",$subitems['ff']); ?> {
				font-family: '<?php echo str_replace("_"," ",$subitems['ff']); ?>', serif;
				font-size: 48px;
			  }
			</style>
		<?php } 
		
		echo '<div class="clsCategory_block">';
		echo '<h2 class="'.str_replace(" ","_",$subitems['ff']).'">'.$key.'</h2>';
		if(count($subitems) > 0){
			echo '<ul id="me-select-list">';
			foreach($subitems as $items){
			
				if(is_object($items)) {/*style="background: '.$items->color.'" */
				echo '<li id="li_'.$items->id.'" >';
				if(isset($checkedlist[$items->id]))
				{
					echo '<input checked="checked"  id="checklist_'.$items->id.'" name="checklist[]" type="checkbox" onclick="updatecheckery('.$checklist_id.');" value="'.$items->id.'" />';
				}
				else
				{
					echo '<input  id="checklist_'.$items->id.'" name="checklist[]" type="checkbox" onclick="updatecheckery('.$checklist_id.');" value="'.$items->id.'" />';
				}
echo '<label for="cb1">';				
				echo '<span style="color:'.$items->color.'">'.stripslashes($items->check_item).'</span></label>';
				echo '</li>';
				}
			}
			echo '</ul>';
			}
			echo '</div>';
		

	}

echo '</ul>';

$currentid=0;

if(isset($_GET['id']))
{
	$currentid=intval($_GET['id']);
	unset($_GET['id']);
}
$qs = http_build_query($_GET);


if($qs != '')
{
	$qs='?'.$qs;
}

$_GET['id']=$currentid;


echo '<input type="hidden" name="currentid" id="currentid_'.$checklist_id.'" value="'.$currentid.'"/>

<input type="hidden" name="location_search" id="location_search" value="'.$qs.'"/>

<input type="hidden" name="page_url" id="page_url_'.$checklist_id.'" value=""/>

<input type="hidden" name="page_id" id="page_id_'.$checklist_id.'" value="'.get_the_ID().'"/>


</form></div>';

/*<input type="button" class="btn btn-primary active" value="Save" onclick="checkerysaveitem('.$checklist_id.');"/>

*/
?>





<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.me-select label').click(function(){

		 jQuery(this).prev().click();
	});
	
});
</script>



