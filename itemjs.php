<?php

echo '<script src="'.plugins_url("js/magicselection.js", __FILE__).'" type="text/javascript"></script>';

echo '<script type="text/javascript">

		(function() {

			var selList = document.getElementById( \'me-select-list\' ),

				items = selList.querySelectorAll( \'li\' );

			

			// fill the initial checked elements (mozilla)

			[].slice.call( items ).forEach( function( el ) {

				el.parentNode.className = el.checked ? \'selected\' : \'\';

			} );



			function checkUncheck( el ) {

				el.parentNode.className = el.checked ? \'\' : \'selected\';

				el.checked = !el.checked;

			}



			new magicSelection( selList.querySelectorAll( \'li > input[type="checkbox"]\' ), {

				onSelection : function( el ) { checkUncheck( el ); },

				onClick : function( el ) {

					el.parentNode.className = el.checked ? \'selected\' : \'\';

				}

			} );



		} )();

</script>';?>

<script type="text/javascript">

$= jQuery.noConflict(); 

	function updatecheckery(mids)

	{

	

		chk=document.getElementsByName("checklist[]");

		

		var listarray = [];

		var j=0;

		for(var i=0;i<chk.length;i++)

		{

			if(chk[i].checked)

			{

				

				if(chk[i].value != '')

				{

					listarray[j] = chk[i].value;

					

					//var bcolor=document.getElementById("color_"+chk[i].value).value;

					//var mcolor=document.getElementById("map_color_"+chk[i].value).value;

					

					

					

					//$('#li_'+chk[i].value).css('background-color',bcolor);

					//$('#tli_'+chk[i].value).css('background-color',mcolor);

					

					

					

					

					//var updatedUrl =addParamToUrl(j, chk[i].value);

					//window.history.pushState({path:updatedUrl},'',updatedUrl);

					j++;

				}

				

			}

			else

			{

				//$('#li_'+chk[i].value).css('background-color','');

				//$('#tli_'+chk[i].value).css('background-color','');

			}

		}

		



		/*if (qurl == '')

		{

			qurl += '?checkery=' + listarray;

		}

		else

		{

			qurl += '&checkery=' + listarray;

		}*/

			

			

		var cid=document.getElementById("currentid_"+mids).value;

			

				

		checkerysaveitem(mids,listarray,cid);

		

		

				

		

	}

	

	function addParamToUrl(param, value)

	{

		

			

            //check if param exists

            var result = new RegExp(param + "=([^&]*)", "i").exec(window.location.search);

            result = result && result[1] || "";

 

            //added seperately to append ? before params

            var loc = window.location;

            var url = loc.protocol + '//' + loc.host + loc.pathname + loc.search;



            //param doesn't exist in url, add it

            if (result == '')

            {

                //doesn't have any params

                if (loc.search == '')

                {

					

                    url += "?" + param + '=' + value;

                }

                else

                {

                    url += "&" + param + '=' + value;

                }

            }

 

            //return the finished url

            return url;

        

	}

	

	function checkerysaveitem(midss,chkitem,cids)

	{

		var purl=document.getElementById("page_url_"+midss).value;

		var pageid=document.getElementById("page_id_"+midss).value;

		

		$.post("<?php echo get_option('siteurl'); ?>/wp-admin/admin-ajax.php", {action:"checkery_save", "pageurl": encodeURIComponent(purl),"mid": encodeURIComponent(midss),"pageid": encodeURIComponent(pageid),"chkitem": encodeURIComponent(chkitem),"currentid": encodeURIComponent(cids)}, function(str)	{

		

		if(str > 0)

		{

			document.getElementById("currentid_"+midss).value=str;

			var retid=document.getElementById("currentid_"+midss).value;

			

			var qurl=document.getElementById("location_search").value;

			if (qurl == '')

			{

				qurl += '?id=' + retid;

			}

			else

			{

				qurl += '&id=' + retid;

			}

			

			

			var loc = window.location;

			var url = loc.protocol + '//' + loc.host + loc.pathname;		

			window.history.pushState({path:url},'',url);

			var updatedUrl = url + qurl	;

			

			document.getElementById("page_url_"+midss).value=updatedUrl;

			window.history.pushState({path:updatedUrl},'',updatedUrl);

		}

		

		

		

		

		//alert('Your selected item has been saved successfully. Thank you.');

		

		

});





	}



</script>





