<?php

/*Plugin Name: Checkery

Plugin URI: http://www.envisionyourwebsite.com

Description: Checkery. 

Author: Envision Your Website

Version: 1.0

Author URI: http://www.envisionyourwebsite.com

Copyright 2014 envisionyourwebsite.com  (email : tgarner@envisionyourwebsite.com)



This program is free software; you can redistribute it and/or modify

it under the terms of the GNU General Public License, version 2, as 

published by the Free Software Foundation.



This program is distributed in the hope that it will be useful,

but WITHOUT ANY WARRANTY; without even the implied warranty of

MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

GNU General Public License for more details.



You should have received a copy of the GNU General Public License

along with this program; if not, write to the Free Software

Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

ob_start();

define ( 'CHECKERY_PLUGIN_URL', plugin_dir_url(__FILE__));

if( !class_exists('checkery') ):

class checkery{

		function checkery() 

		{ 
				#Add Settings Panel

				add_action( 'admin_menu', array($this, 'checkeryPanel') );

				add_action( 'admin_head', array($this, 'checkery_icon') );

				

				if( $_POST['action'] == 'add_checklist' )

					add_action( 'init', array($this,'addChecklist') );	

				

				if( $_POST['action'] == 'edit_checklist' )

					add_action( 'init', array($this,'editChecklist') );	

					

				if( $_POST['action'] == 'add_checkeryitem' )

					add_action( 'init', array($this,'addCheckeryitem') );	

				

				if( $_POST['action'] == 'edit_checkeryitem' )

					add_action( 'init', array($this,'editCheckeryitem') );	

					

					

				if( $_POST['action'] == 'add_category' )

					add_action( 'init', array($this,'addCategory') );	

				

				if( $_POST['action'] == 'edit_category' )

					add_action( 'init', array($this,'editCategory') );
				

				if( $_POST['action'] == 'add_colorgroup' )

					add_action( 'init', array($this,'addColorgroup') );	

				

				if( $_POST['action'] == 'edit_colorgroup' )

					add_action( 'init', array($this,'editColorgroup') );

					

					

				if( $_POST['action'] == 'import_checklist' )

					add_action( 'init', array($this,'Importchecklist') );
					

				add_action('wp_ajax_mapkey_info', array($this,  'ajaxUpdateMapkey'));	

				add_action('wp_ajax_checkery_save', array($this,  'ajaxSavecheckery'));	

				add_action('wp_ajax_nopriv_checkery_save',array($this,  'ajaxSavecheckery'));				

				add_shortcode('checklist', array($this, 'checkeryShortCode') );

				add_filter('widget_text', 'do_shortcode');

				add_action('admin_print_scripts', array($this, 'do_jslibs'));

				add_action('admin_print_styles', array($this, 'do_css'));
				add_action( 'media_buttons',array($this,'checkery_media_button'),11 );
				
				

				//INSTALL TABLE

				register_activation_hook( __FILE__, array($this, 'checkeryInstall') );

		}

		function checkeryPanel()

		{
			add_menu_page('checkery', 'Checkery', 10, 'checkery', array($this, 'checkerysetup'),'div' );

			add_submenu_page( 'checkery', '', 'Checklist', 10, 'checkery', array($this, 'checkerysetup') );

			//add_submenu_page( 'checkery', '', 'View Checklist', 10, 'checkerylist', array($this, 'checkeryList_item') );

			add_submenu_page( 'checkery', '', 'Color Groups', 10, 'colorgroup', array($this, 'checkerycolorgroup') );

			add_submenu_page( 'checkery', '', 'Track Checklist', 10, 'trackcheckery', array($this, 'checkeryTrack') );

		}

		

		function checkerysetup()

		{

			

			global $wpdb;

				
			if(trim($_GET['flag']) == 'add')

			{

				require ("checkerysetup.php");


			}

			else if(trim($_GET['flag']) == 'edit')

			{

				require ("checkerysetup.php");

			}

			else if(trim($_GET['flag']) == 'addoption')

			{

				require ("categoryitem.php");

			}

			else if(trim($_GET['flag']) == 'editoption')

			{

				require ("categoryitem.php");

			}

			else if(trim($_GET['flag']) == 'categoryadd'){

				require ("category.php");

			}
			
			else if(trim($_GET['flag']) == 'editcatoption'){
			
				require ("category.php");

			}
			
			else if(trim($_GET['flag']) == 'categoryedit'){

				require ("category.php");

			}

			else if(trim($_GET['flag']) == 'deleteoption')

			{

					$qry="DELETE FROM `".$wpdb->prefix ."checkery_items` WHERE `id` ='".intval($_GET['itemid'])."'";

					$wpdb->query($qry);

					wp_redirect(admin_url('admin.php?page=checkery&flag=edit&id='.intval($_GET['itemid'])));

			}
			
			else if(trim($_GET['flag']) == 'deletecatoption')

			{
					
					$qry="DELETE FROM `".$wpdb->prefix ."checkery_category` WHERE `id` ='".intval($_GET['category_id'])."'";
					
					$qry1="DELETE FROM `".$wpdb->prefix ."checkery_items` WHERE `category` ='".intval($_GET['category_id'])."'";

					$wpdb->query($qry);
					$wpdb->query($qry1);

					wp_redirect(admin_url('admin.php?page=checkery&flag=edit&id='.$_GET['id']));

			}

			else if(trim($_GET['flag']) == 'delete')

			{

					$qry="DELETE FROM `".$wpdb->prefix ."checkery_checklist` WHERE `id` ='".intval($_GET['id'])."'";
					
					$qry1="DELETE FROM `".$wpdb->prefix ."checkery_category` WHERE `checklist_id` ='".intval($_GET['id'])."'";
					$qry2="DELETE FROM `".$wpdb->prefix ."checkery_items` WHERE `checklist_id` ='".intval($_GET['id'])."'";

					$wpdb->query($qry);
					$wpdb->query($qry1);
					$wpdb->query($qry2);

					wp_redirect(admin_url('admin.php?page=checkery'));

			}

			else if(trim($_GET['flag']) == 'importcsv')

			{

				require ("importcsv.php");

			}

			else

			{

				require ("maincategorylist.php");

			}

			

		}

		function do_css()

		{

			wp_enqueue_style('thickbox');

		}

		

		function do_jslibs()

		{

			wp_enqueue_script('editor');

			wp_enqueue_script('thickbox');

			add_action( 'admin_head', array($this, 'ShowTinyMCE'));

		}

		function ShowTinyMCE() {

	// conditions here

	wp_enqueue_script( 'common' );

	wp_enqueue_script( 'jquery-color' );

	wp_print_scripts('editor');

	if (function_exists('add_thickbox')) add_thickbox();

	wp_print_scripts('media-upload');

	if (function_exists('wp_tiny_mce')) wp_tiny_mce();

	wp_admin_css();

	wp_enqueue_script('utils');

	do_action("admin_print_styles-post-php");

	do_action('admin_print_styles');

}

		function checkery_icon()

		{

		

			echo '<style type="text/css">

			#toplevel_page_checkery div.wp-menu-image {

			  background-image: url("'.plugins_url("images/menu.png", __FILE__).'") ;
				background-origin: padding-box;
				background-position: center center;
				background-repeat: no-repeat;
				background-size: 67% auto;

			} 

			#toplevel_page_checkery:hover div.wp-menu-image, #toplevel_page_checkery.current div.wp-menu-image, #toplevel_page_checkery.wp-has-current-submenu div.wp-menu-image {

			  background-attachment: scroll;
				background-clip: border-box;
				background-image: url("'.plugins_url("images/menu.png", __FILE__).'") ;
				background-origin: padding-box;
				background-position: center center;
				background-repeat: no-repeat;
				background-size: 67% auto;

			}

			</style>';

			

			



		}

		

		function checkeryList_item()

		{

			

			global $wpdb;

				

			if(trim($_GET['flag']) == 'addoption')

			{

				require ("categoryitem.php");

			}

			else if(trim($_GET['flag']) == 'editoption')

			{

				require ("categoryitem.php");

			}

			

			else if(trim($_GET['flag']) == 'categoryadd'){

				require ("category.php");

			}

			else

			{

				require ("subcategorylist.php");

			}

			

		}

		

		function checkeryTrack()

		{

			global $wpdb;

			require ("tracklist.php");

		}

		

		

		function checkerycolorgroup()

		{

			global $wpdb;

			if(trim($_GET['flag']) == 'add')

			{

				require ("colorgroup.php");

			}

			else if(trim($_GET['flag']) == 'edit')

			{

				require ("colorgroup.php");

			}
			
			else if(trim($_GET['flag']) == 'delete')

			{

				$qry="DELETE FROM `".$wpdb->prefix ."checkery_colorgroup` WHERE `id` ='".intval($_GET['id'])."'";

				$wpdb->query($qry);
				$_POST['notice'] = __('Category has been added successfully', 'checkery');
				wp_redirect( admin_url('admin.php?page=colorgroup'));


			}
			

			else{require ("colorgroups.php");}

		}

		

		function ajaxUpdateMapkey()

		{

			 global $wpdb;

			 

			 $sub_id=intval(urldecode($_POST['kid']));

			 

			  $kval='';

			  $colorkey='';

			 $mapcolorkey='';

			 if(isset($_POST['kval']))

			 {

			 	$kval=urldecode(trim($_POST['kval']));

			 }

			 

			 if(isset($_POST['colorkey']))

			 {

			 	$colorkey=urldecode(trim($_POST['colorkey']));

			 }

			 

			  if(isset($_POST['mapcolorkey']))

			 {

			 	$mapcolorkey=urldecode(trim($_POST['mapcolorkey']));

			 }

			 

			 

			 			

			 

			 if($kval != 'undefined' && $kval != '')

			 {

			 		$qry="UPDATE `".$wpdb->prefix ."checkery_subcategory` SET 

					map_name='".mysql_escape_string($kval)."'					

					WHERE sub_id = '".$sub_id."'";

					$wpdb->query($qry);

			 }

			if($colorkey != 'undefined' && $colorkey != '')

			 {

			 		$qry="UPDATE `".$wpdb->prefix ."checkery_subcategory` SET 

					color_key='".mysql_escape_string($colorkey)."'

					WHERE sub_id = '".$sub_id."'";

					$wpdb->query($qry);

			 }

			 

			 if($mapcolorkey != 'undefined' && $mapcolorkey != '')

			 {

			 		$qry="UPDATE `".$wpdb->prefix ."checkery_subcategory` SET 

					map_color_key='".mysql_escape_string($mapcolorkey)."'

					WHERE sub_id = '".$sub_id."'";

					$wpdb->query($qry);

			 }

			 

			  

		}

		

		function ajaxSavecheckery()

		{

			

			global $wpdb;

			$pageurl=urldecode(trim($_POST['pageurl']));

			$mid=urldecode(trim($_POST['mid']));

			$pageid=urldecode(trim($_POST['pageid']));

			$user_id = get_current_user_id();

			$list_item=urldecode($_POST['chkitem']);

			$currentid=urldecode(trim($_POST['currentid']));

			

			 $wpdb->get_results("SELECT * FROM `".$wpdb->prefix ."checkery_track` WHERE ID='".$currentid."'");

			

			

			

			if(intval($currentid) > 0 && intval($wpdb->num_rows) > 0)

			{

				$qry="UPDATE `".$wpdb->prefix ."checkery_track` SET 

					page_id='".mysql_escape_string($pageid)."',

					list_item='".mysql_escape_string($list_item)."',

					page_url='".$pageurl."',

					user_id='".$user_id."',

					ip_address='".$_SERVER['REMOTE_ADDR']."',

					date_time='".date('Y-m-d H:i:s')."' WHERE ID='".mysql_escape_string($currentid)."'";

					

					

					$wpdb->query($qry);

					

					echo $currentid;exit;

			}

			else

			{

					$itemarray=explode(',',$list_item);

					if(count($itemarray) == 1)

					{

					

					if(intval($currentid) > 0)

					{

						$qry="INSERT INTO `".$wpdb->prefix ."checkery_track` SET 

						ID='".$currentid."',

						page_id='".mysql_escape_string($pageid)."',

						list_item='".mysql_escape_string($list_item)."',

						page_url='".$pageurl."',

						main_cat_id='".$mid."',

						user_id='".$user_id."',

						date_time='".date('Y-m-d H:i:s')."',

						ip_address='".$_SERVER['REMOTE_ADDR']."'";

						

						$wpdb->query($qry);

						$lastid = $wpdb->insert_id;

						

						echo $lastid;exit;

					}

					else

					{

						$qry="INSERT INTO `".$wpdb->prefix ."checkery_track` SET 

						page_id='".mysql_escape_string($pageid)."',

						list_item='".mysql_escape_string($list_item)."',

						page_url='".$pageurl."',

						main_cat_id='".$mid."',

						user_id='".$user_id."',

						date_time='".date('Y-m-d H:i:s')."',

						ip_address='".$_SERVER['REMOTE_ADDR']."'";

						

						$wpdb->query($qry);

						$lastid = $wpdb->insert_id;

						

						echo $lastid;exit;

					}

					

						

					

					}

					

					

					

			}

			

			/*$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_track WHERE main_cat_id='".mysql_escape_string($mid)."' AND ip_address='".$_SERVER['REMOTE_ADDR']."'");

				if($wpdb->num_rows == 0)

				{

					$qry="INSERT INTO `".$wpdb->prefix ."checkery_track` SET 

					page_id='".mysql_escape_string($pageid)."',

					list_item='".mysql_escape_string($list_item)."',

					page_url='".$pageurl."',

					main_cat_id='".$mid."',

					user_id='".$user_id."',

					date_time='".date('Y-m-d H:i:s')."',

					ip_address='".$_SERVER['REMOTE_ADDR']."'";

					

					$wpdb->query($qry);

					$lastid = $wpdb->insert_id;

					

					echo $lastid;exit;

				}

				else

				{

					$qry="UPDATE `".$wpdb->prefix ."checkery_track` SET 

					page_id='".mysql_escape_string($pageid)."',

					list_item='".mysql_escape_string($list_item)."',

					page_url='".$pageurl."',

					user_id='".$user_id."',

					date_time='".date('Y-m-d H:i:s')."' WHERE main_cat_id='".mysql_escape_string($mid)."' AND ip_address='".$_SERVER['REMOTE_ADDR']."'";

					

					$wpdb->query($qry);

				}*/
		}

		

		function checkeryShortCode($params)

		{

		 	ob_start();

			global $wpdb;

			$checklist_id = $params['id'];

			$checklist_cat = $wpdb->get_results("SELECT b.*,a.checklist,a.category_font,a.show_title,a.description FROM `".$wpdb->prefix ."checkery_checklist` a,`".$wpdb->prefix ."checkery_category` b WHERE a.id='".$checklist_id."' AND a.id=b.checklist_id AND a.status='1' AND b.status=1");

			

			$checklist_items = array();

			$colorgroup=array();

			

			foreach($checklist_cat as $categories){

				$cat_id = $categories->id;

				$cat_name = $categories->category;

				$checklist_title = $categories->checklist;
				$show_title = $categories->show_title;
				
				$category_font = $categories->category_font;

				$checklist_description = $categories->description;

				$checklist_items_query=$wpdb->get_results("SELECT a.id,a.check_item,b.color,b.color_group FROM `".$wpdb->prefix ."checkery_items` a,`".$wpdb->prefix."checkery_colorgroup` b WHERE a.category =".$cat_id." AND a.colorgroup = b.id AND a.status=1");

				$checklist_items[$cat_name] = $checklist_items_query;
				$checklist_items[$cat_name]['ff'] =$category_font;
				
				
			}
			$colorgrouparray=array();

			foreach($checklist_items as $legenditems)

			{

				if(count($legenditems) > 0)

				{

					foreach($legenditems as $litems)

					{

						$colorgrouparray[$litems->color_group]=$litems->color;

					

					}

				}

			}
			

			$checkedlist=array();

			if(isset($_GET['id']))

			{

				$urlid=intval($_GET['id']);

			

				$recchecked= $wpdb->get_results("SELECT * FROM `".$wpdb->prefix ."checkery_track` WHERE ID='".$urlid."' AND main_cat_id='".$checklist_id."'");

				$listitems=$recchecked[0]->list_item;

				$checkedlist=explode(',',$listitems);

				$checkedlist = array_flip($checkedlist);

			}


			require("listitems.php");

			require_once("itemjs.php");

			return ob_get_clean();

			

		}

		

		function checkery_media_button()

		{

			

		

		

	global $pagenow, $typenow, $wp_version,$wpdb;

	echo '<style type="text/css">

.scheck_wrap{

	border: 1px solid #DFDFDF;

	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);

}

.scheck_shortcode{

	border-bottom: 1px solid #CCC;

	padding: 0px;

	background: #FFF;

}

.scheck_shortcode_name{

	cursor: pointer;

	padding: 10px;

}

.scheck_shortcode_name:hover{

	background: #fbfbfb;

}

.scheck_params{

	border: 1px solid #DFDFDF;

	background: #F9F9F9;

	margin: 0 -1px -1px;

	padding: 20px;

	display: none;

}

.scheck_insert{

	background: linear-gradient(to bottom, #09C, #0087B4);

	color: #FFF;

	padding: 5px 15px;

	border: 1px solid #006A8D;

	font-weight: bold;

}



.scheck_insert:hover{

	opacity: 0.8;

}



.scheck_toggle{

	background: url('.plugins_url("images/toggle-arrow.png", __FILE__).') no-repeat;

	float: right;

	width: 16px;

	height: 16px;

	opacity: 0.4;

}



.scheck_share_iframe{

	background: #FFFFFF;

	border: 1px solid #dfdfdf;

	-webkit-border-radius: 5px;

	-moz-border-radius: 5px;

	border-radius: 5px;

	-moz-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);

	-webkit-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);

	box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);

}

#TB_window

{

	background: #F2F2F2;

}

</style>';

	

	

$cats = $wpdb->get_results("SELECT id,checklist FROM ".$wpdb->prefix."checkery_category WHERE status='1'");

			$shortcodes=array();

			foreach($cats as $res)

			{

				$scheck_options[$res->id]=stripslashes(trim($res->checklist));

			}

			

			

			

			//echo '<pre>';print_r($shortcodes);exit;



	/** Only run in post/page creation and edit screens */

	if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) ) {

		/* check current WP version */

		if ( version_compare( $wp_version, '3.5', '<' ) ) {

			$img = '<img src="'.plugins_url("images/sicon.png", __FILE__).'" />';

			$output = '<a href="#TB_inline?width=640&inlineId=choose-download" class="thickbox" title="' . __( 'Insert Order Form Shortcode', 'edd' ) . '">' . $img . '</a>';

		} else {

			$img = '<img src="'.plugins_url("images/sicon.png", __FILE__).'" />';

			$output = '<a href="#TB_inline?width=640&inlineId=choose-checkery-shortcode" class="thickbox button new-iom-shortcode" title="' . sprintf( __( '%s', 'edd' ), "Checkery Shortcode") . '" style="padding-left: .4em;">' . $img . sprintf( __( '%s', 'edd' ), "Checkery Shortcode" ) . '</a>';

			

			$output .= "<div class='short-list-checkery wp_themeSkin' style='margin-top:0px; padding-top:0px; display:none;' id='choose-checkery-shortcode'><h2>Checkery Shortcode</h2>";



			

			$output.='<div class="scheck_wrap">';





if(count($scheck_options) > 0)

{

foreach($scheck_options as $key=>$value){

	if($key != '_version_fix'){

		$output.='<div class="scheck_shortcode"><div class="scheck_shortcode_name">'.$value.'</div>';

		$output.='<div class="scheck_params">';

		$output.='<input type="button" class="scheck_insert cupid-blue" data-name="' . $key . '" value="Insert Shortcode"/>';

		$output.='</div>';

		$output.='</div>';

	}

}

}







$output.='</div>';

		

			$output .= "</div>";

		}

	}

	

	echo $output;

	

	

	

	

	?>
<script type="text/javascript">	$j= jQuery.noConflict();</script>
<script type="text/javascript">

$j(document).ready(function(){

	

	$j('.scheck_shortcode_name').append('<span class="scheck_toggle"></span>');

	

	$j('.scheck_insert').click(function(){

		var params = '';

		var scname = $j(this).attr('data-name');

		var sc = '';

		

		$j(this).parent().children().find('input[type="text"]').each(function(){

			if($j(this).val() != ''){

				attr = $j(this).attr('data-param');

				val = $j(this).val();

				params += attr + '="' + val + '" ';

			}

		});

		

		if(wsc(scname)){

			name = '"' + scname + '"';

		}else{

			name = scname;

		}

		

		

		

		sc = '[checklist id=' + name + ']';

		

		if( typeof parent.send_to_editor !== undefined ){

			parent.send_to_editor(sc);

		}

		

	});

	

	

	

	$j('.scheck_shortcode_name').click(function(e){

		$j('.scheck_params').slideUp();

		if($j(this).next('.scheck_params').is(':visible')){

			$j(this).next('.scheck_params').slideUp();

		}else{

			$j(this).next('.scheck_params').slideDown();

		}

	})

	

});



var scheck_closeiframe = function(){

	$j('.scheck_share_iframe').remove();

}



function wsc(s){

	if(s == null)

		return '';

	return s.indexOf(' ') >= 0;

}

</script>
<?php



		

		}     

		

		function addChecklist()  

		{

			global $wpdb;

			$checklist=trim($_POST['checklist']);

			$description=trim($_POST['description']);

			

			

			if($checklist != '')

			{

				$status=trim($_POST['status']);
				
				$category_font = '';
				
				if(isset($_POST['category_font'])) { $category_font = $_POST['category_font']; }
				
				$show_title= 0;
				if(isset($_POST['show_title'])) { $show_title = trim($_POST['show_title']); } 

				$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_checklist WHERE checklist='".mysql_escape_string($checklist)."'");

				if($wpdb->num_rows == 0)

				{

					$qry="INSERT INTO `".$wpdb->prefix ."checkery_checklist` SET 

					checklist='".mysql_escape_string($checklist)."',

					description='".mysql_escape_string($description)."',

					status='".$status."',
					
					show_title='".$show_title."',
					
					category_font='".$category_font."',

					date_created='".date('Y-m-d H:i:s')."'";

					$wpdb->query($qry);
					$lastid = $wpdb->insert_id;
					
					wp_redirect( admin_url('admin.php?page=checkery&flag=edit&id='.$lastid.'&addsuccess=1'));
					

	

				}

				else

				{

					$_POST['errornotice'] = __('This Checklist is already exists', 'checkery');

	

				}

			}

			

		 

		}

		

		function editChecklist()

		{

			global $wpdb;

			$checklist=trim($_POST['checklist']);

			$description=trim($_POST['description']);

			if($checklist != '')

			{
				$show_title= 0;
				if(isset($_POST['show_title'])) { $show_title = trim($_POST['show_title']); } 
				
				$category_font = '';
				
				if(isset($_POST['category_font'])) { $category_font = $_POST['category_font']; }
				
				$status=trim($_POST['status']);

				$cat_id=trim($_POST['cat_id']);

				$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_checklist WHERE checklist='".mysql_escape_string($checklist)."' AND id != '".$cat_id."'");

				if($wpdb->num_rows == 0)

				{

					$qry="UPDATE `".$wpdb->prefix ."checkery_checklist` SET 

					checklist='".mysql_escape_string($checklist)."',

					description='".mysql_escape_string($description)."',

					status='".$status."',
					show_title='".$show_title."',
					
					category_font='".$category_font."',

					date_created='".date('Y-m-d H:i:s')."' WHERE id = '".$cat_id."'";

					$wpdb->query($qry);

					$_POST['notice'] = __('Checklist has been Updated successfully', 'checkery');

	

				}

				else

				{

					$_POST['errornotice'] = __('This Checklist is already exists', 'checkery');

	

				}

			}

		}

		

		function addCategory(){
			global $wpdb;

			$category=trim($_POST['category']);			
			$category_font=trim($_POST['category_font']);	
			$checklist_id=trim($_POST['checklist_id']);
			$status=trim($_POST['status']);
			if($checklist_id != ''){
			if($category != '' )
			{
				$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_category WHERE category='".mysql_escape_string($category)."' AND checklist_id='".$checklist_id."'");
				if($wpdb->num_rows == 0)

				{

					$qry="INSERT INTO `".$wpdb->prefix ."checkery_category` SET 
					category='".mysql_escape_string($category)."',					
					category_font='".mysql_escape_string(str_replace(" ","_",$category_font))."',
					status='".$status."',
					checklist_id='".$checklist_id."',
					date_created='".date('Y-m-d H:i:s')."'"; 
					$result = $wpdb->query($qry);
					 										
					$_POST['notice'] = __('Category has been added successfully', 'checkery');
					wp_redirect( admin_url('admin.php?page=checkery&flag=edit&id='.$checklist_id));
				}
				else
				{$_POST['errornotice'] = __('This Category already exists', 'checkery');}

			}

			}
			else
			{
				$_POST['errornotice'] = __('Checklist ID Empty', 'checkery');
			}
		}


		function editCategory(){
			global $wpdb;

			$category=trim($_POST['category']);			
			$category_id = '';
			if(isset($_POST['id'])) { $category_id = $_POST['id'];}
			$category_font=trim($_POST['category_font']);	
			$checklist_id=trim($_POST['checklist_id']);
			$status=trim($_POST['status']);
			if($checklist_id != ''){
			if($category != '' )
			{
				$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_category WHERE category='".mysql_escape_string($category)."' AND WHERE id != '".$category_id."'");
				if($wpdb->num_rows == 0)

				{

					$qry="UPDATE `".$wpdb->prefix ."checkery_category` SET 
					category='".mysql_escape_string($category)."',					
					category_font='".mysql_escape_string(str_replace(" ","_",$category_font))."',
					status='".$status."' WHERE ID=".$category_id; 
					$result = $wpdb->query($qry);
					 										
					$_POST['notice'] = __('Category has been added successfully', 'checkery');
					wp_redirect( admin_url('admin.php?page=checkery&flag=edit&id='.$checklist_id));
				}
				else
				{$_POST['errornotice'] = __('This Category already exists', 'checkery');}

			}

			}
			else
			{
				$_POST['errornotice'] = __('Checklist ID Empty', 'checkery');
			}
		}
		

		

		function addCheckeryitem()

		{

			global $wpdb;			

			$category=trim($_POST['category']);

			$check_item=trim($_POST['check_item']);

			$color_group=trim($_POST['color_group']);

			$checklist_id=trim($_POST['checklist_id']);

			

			$status=trim($_POST['status']);

			if($checklist_id != ''){

			

			if($check_item != '' )

			{

				$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_items WHERE check_item='".mysql_escape_string($check_item)."' AND category='".mysql_escape_string($category)."'");

				 

				if($wpdb->num_rows == 0)

				{

					$qry="INSERT INTO `".$wpdb->prefix ."checkery_items` SET 

					category='".mysql_escape_string($category)."',

					check_item='".mysql_escape_string($check_item)."',

					status='".$status."',

					colorgroup='".$color_group."',

					checklist_id='".$checklist_id."',

					date_created='".date('Y-m-d H:i:s')."'";

					$wpdb->query($qry);
					wp_redirect( admin_url('admin.php?page=checkery&flag=edit&id='.$checklist_id));
					$_POST['notice'] = __('Category item has been added successfully', 'checkery');

	

				}

				else

				{

					$_POST['errornotice'] = __('This Category Item already exists', 'checkery');

	

				}

			}

			}else

				{

					$_POST['errornotice'] = __('Checklist ID Empty', 'checkery');	

				}

		}

		function editCheckeryitem()

		{

			global $wpdb;

			

			$category=trim($_POST['category']);

			$check_item=trim($_POST['check_item']);

			$color_group=trim($_POST['color_group']);

			$checklist_id=trim($_POST['checklist_id']);

			$item_id=$_POST['id'];			

			$status=trim($_POST['status']);

			

			if($checklist_id != ''){

			if($check_item != '')

			{				

				//$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_items WHERE check_item='".mysql_escape_string($check_item)."' AND id != '".$item_id."'");
				

				//if($wpdb->num_rows == 0)

				//{

					$qry="UPDATE `".$wpdb->prefix ."checkery_items` SET 

					check_item='".mysql_escape_string($check_item)."',

					category='".mysql_escape_string($category)."',

					status='".$status."',

					colorgroup='".$color_group."'

					WHERE id = ".$item_id;

					$wpdb->query($qry);
					wp_redirect( admin_url('admin.php?page=checkery&flag=edit&id='.$checklist_id));

					$_POST['notice'] = __('Cateogry Item has been updated successfully', 'checkery');	

				//}

				/*else

				{

					$_POST['errornotice'] = __('This Cateogry Item is already exists', 'checkery');	

				}
*/
			 }

			}

		}
		function addColorgroup()

		{

			global $wpdb;			

			$color_group=trim($_POST['color_group']);

			$color=trim($_POST['color_key']);			

			$status=trim($_POST['status']);

			

			if($color_group != '' )

			{

				$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_colorgroup WHERE color_group='".mysql_escape_string($color_group)."'");

				 

				if($wpdb->num_rows == 0)

				{

					$qry="INSERT INTO `".$wpdb->prefix ."checkery_colorgroup` SET 

					color_group='".mysql_escape_string($color_group)."',

					color='".mysql_escape_string($color)."',

					status='".$status."',

					date_created='".date('Y-m-d H:i:s')."'"; 

					$wpdb->query($qry); 

					$_POST['notice'] = __('Color Group has been added successfully', 'checkery');

   					wp_redirect( admin_url('admin.php?page=colorgroup&addnotice=1'));



				}

				else

				{

					$_POST['errornotice'] = __('This Color Group already exists', 'checkery');

					wp_redirect( admin_url('admin.php?page=colorgroup'));

				}

			}			

		}

		function editColorgroup()

		{

			global $wpdb;			

			$color_group=trim($_POST['color_group']);

			$color=trim($_POST['color_key']);			

			$status=trim($_POST['status']);

			$id=trim($_POST['id']);

			

			if($color_group != '')

			{				

				//$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_colorgroup WHERE color_group='".mysql_escape_string($color_group)."' AND id != '".$id."'");

				//if($wpdb->num_rows == 0)

				//{

					$qry="UPDATE `".$wpdb->prefix ."checkery_colorgroup` SET 

					color_group='".mysql_escape_string($color_group)."',

					color='".mysql_escape_string($color)."',

					status='".$status."'

					WHERE id = ".$id;

					$wpdb->query($qry);

					$_POST['notice'] = __('Color Group has been updated successfully', 'checkery');	
					wp_redirect( admin_url('admin.php?page=colorgroup&updatenotice=1'));
				/*}

				else

				{

					$_POST['errornotice'] = __('This Color Group is already exists', 'checkery');	

				}*/

			 }			

		}

		function Importchecklist()

		{

		//echo '<pre>';print_r($_FILES);exit;

			global $wpdb;

			if (empty($_FILES['csv_import']['tmp_name']))

			{

				$this->log['error'][] = 'No file uploaded, aborting.';

				$this->print_messages();

				return;

			}

			$checklist_name = trim($_POST['check_list_name']);
			
			//$category_font = '';
				
			//if(isset($_POST['category_font'])) { $category_font = $_POST['category_font']; }
			
			if($checklist_name == ''){
				 $_POST['notice'] = __('Please Enter Checklist Name', 'checkery');
				 return;
			}
			else
			{
				 $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_checklist WHERE checklist='".mysql_escape_string($checklist_name)."'");

				if($wpdb->num_rows == 0)

				{
					

					$qry="INSERT INTO `".$wpdb->prefix ."checkery_checklist` SET 

					checklist='".mysql_escape_string($checklist_name)."',

					description='',

					status=1,
					show_title=0,
					
					category_font='',

					date_created='".date('Y-m-d H:i:s')."'";

					$wpdb->query($qry);
					$lastid = $wpdb->insert_id;
					
					//wp_redirect( admin_url('admin.php?page=checkery&flag=edit&id='.$lastid.'&addsuccess=1'));

				
				}
			}
			

			$checklist_id=$lastid; //trim($_POST['checklist_id']);		

			$tmp_name = $_FILES['csv_import']['tmp_name'];

			$filename = $_FILES['csv_import']['name'];

		 	$uploadpath=date('YmdHis').rand(0,10).'_'.$filename;

			$puploads=dirname(__FILE__)."/uploadcsv/".$uploadpath;

			chmod(dirname(__FILE__)."/uploadcsv", 0777);

		 if(move_uploaded_file($tmp_name,$puploads))

		 {

			$row = 1;

			$newarray=array();

			if (($handle = fopen($puploads, "r")) !== FALSE) {

				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

					if($row > 1)

					{

						$newarray['Category'][]=$data[0];

						$newarray['color_group'][]=$data[1];

						$newarray['item'][]=array('Category'=>$data[0],'color_group'=>$data[1],'item'=>$data[2]);
					}
					$row++;

				}

				fclose($handle);

			}
		 }		 
		//echo '<pre>'; print_r($newarray); echo '</pre>'; die;
		 if(count($newarray['Category']) > 0)

		 {

		 	foreach($newarray['Category'] as $catitems)

			{

				 $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_category WHERE category='".mysql_escape_string($catitems)."'  AND checklist_id='".$checklist_id."'");

				if($wpdb->num_rows == 0)

				{

					$qry="INSERT INTO `".$wpdb->prefix ."checkery_category` SET 

					category='".mysql_escape_string($catitems)."',
					
					category_font='',

					status='1',

					checklist_id='".$checklist_id."',

					date_created='".date('Y-m-d H:i:s')."'";

					$wpdb->query($qry);

					$lastid = $wpdb->insert_id;

					$importcatarray[$catitems]=$lastid;

						

				}	

				else

				{

					$importcatarray[$catitems]=$results[0]->id;

				}

			}

		 }

		 $importcolorarray=array();

		 if(count($newarray['color_group']) > 0)

		 {

		 	foreach($newarray['color_group'] as $coloritems)

			{

				 $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_colorgroup WHERE color_group='".mysql_escape_string($coloritems)."'  AND checklist_id='".$checklist_id."'");

				if($wpdb->num_rows == 0)

				{

					$qry="INSERT INTO `".$wpdb->prefix ."checkery_colorgroup` SET 

					color_group='".mysql_escape_string($coloritems)."',

					color='#000000',

					status='1',

					date_created='".date('Y-m-d H:i:s')."'";

					$wpdb->query($qry);

					$lastid = $wpdb->insert_id;

					$importcolorarray[$coloritems]=$lastid;

				}	

				else

				{

					$importcolorarray[$coloritems]=$results[0]->id;

				}

			}

		 }

		 foreach($newarray['item'] as $citems)

		 {

		 	$category=$importcatarray[$citems['Category']];

			$color_group=$importcolorarray[$citems['color_group']];

			$check_item=$citems['item'];			

			$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."checkery_items WHERE check_item='".mysql_escape_string($check_item)."'  AND checklist_id='".$checklist_id."'");

				 

				if($wpdb->num_rows == 0)

				{

					 $qry="INSERT INTO `".$wpdb->prefix ."checkery_items` SET 

					category='".$category."',

					check_item='".mysql_escape_string($check_item)."',

					status='1',

					colorgroup='".$color_group."',

					checklist_id='".$checklist_id."',

					date_created='".date('Y-m-d H:i:s')."'";

					$wpdb->query($qry);

	

				}
		 }
		// $_POST['notice'] = __('Imported successfully', 'checkery');	
		wp_redirect( admin_url('admin.php?page=checkery&flag=edit&id='.$checklist_id.'&addsuccess=1'));
		}

		function print_messages() {

        if (!empty($this->log)) {
	?>

<div class="wrap">
  <?php if (!empty($this->log['error'])): ?>
  <div class="error">
    <?php foreach ($this->log['error'] as $error): ?>
    <p><?php echo $error; ?></p>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  <?php if (!empty($this->log['notice'])): ?>
  <div class="updated fade">
    <?php foreach ($this->log['notice'] as $notice): ?>
    <p><?php echo $notice; ?></p>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<!-- end wrap -->
<?php

            $this->log = array();
    } 
    }
    
    
    function checkeryInstall()
    
    {
    
    global $wpdb;    
    $sql1= "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."checkery_category` (
    
    `id` int(10) NOT NULL AUTO_INCREMENT,
    
    `category` varchar(225) CHARACTER SET latin1 NOT NULL,
	
	`category_font` varchar(225) CHARACTER SET latin1 NOT NULL,
    
    `checklist_id` int(10) NOT NULL,
    
    `status` int(1) NOT NULL,
    
    `date_created` datetime NOT NULL,
    
    PRIMARY KEY (`id`)
    
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    
    $wpdb->query($sql1);
    
    
    
    $sql2= "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."checkery_checklist` (
    
    `id` int(10) NOT NULL AUTO_INCREMENT,
    
    `checklist` varchar(225) CHARACTER SET latin1 NOT NULL,
    
    `description` text CHARACTER SET latin1 NOT NULL,
    
    `show_title` int(1) NOT NULL,
    
    `category_font` varchar(225) CHARACTER SET latin1 NOT NULL,
    
    `status` int(1) NOT NULL,
    
    `date_created` datetime NOT NULL,
    
    PRIMARY KEY (`id`)
    
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    
    $wpdb->query($sql2);
    
    
    
    $sql3= "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."checkery_colorgroup` (
    
    `id` int(10) NOT NULL AUTO_INCREMENT,
    
    `color_group` varchar(225) CHARACTER SET latin1 NOT NULL,
    
    `color` varchar(7) CHARACTER SET latin1 NOT NULL,
    
    `date_created` datetime NOT NULL,
    
    `status` int(1) NOT NULL,
    
    PRIMARY KEY (`id`)
    
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    
    $wpdb->query($sql3);   
    
    
    $sql4= "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."checkery_items` (
    
    `id` int(10) NOT NULL AUTO_INCREMENT,
    
    `check_item` varchar(225) CHARACTER SET latin1 NOT NULL,
    
    `category` int(10) NOT NULL,
    
    `colorgroup` int(10) NOT NULL,
    
    `checklist_id` int(10) NOT NULL,
    
    `status` int(1) NOT NULL,
    
    `date_created` datetime NOT NULL,
    
    PRIMARY KEY (`id`)
    
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    
    $wpdb->query($sql4);
    
    
    $sql5= "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."checkery_track` (
    
    `ID` bigint(20) NOT NULL AUTO_INCREMENT,
    
    `page_id` int(10) NOT NULL,
    
    `list_item` text NOT NULL,
    
    `ip_address` varchar(30) NOT NULL,
    
    `user_id` int(10) NOT NULL,
    
    `date_time` datetime NOT NULL,
    
    `page_url` text NOT NULL,
    
    `main_cat_id` int(10) NOT NULL,
    
    PRIMARY KEY (`ID`)
    
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    
    $wpdb->query($sql5);
    
    
    }	
    function checkry_googlefont_list($key='', $sort='') {					 
    require ("checkrygooglefonts.php");
    
    $fonts = json_decode($fonts);
    
    $items = $fonts->items;
    
    $f=0;
    foreach ($items as $item) {
    
    $font_list[] .= $item->family;
    $f++;
    }
    
    //Return the saved lit of Google Web Fonts
    return $font_list;
    }	    
    }
    
    endif;
    
    if( class_exists('checkery') )
    
    $checkeryobj = new checkery();