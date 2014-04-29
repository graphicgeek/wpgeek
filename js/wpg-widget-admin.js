jQuery(document).ready(function($){
	
	function wpg_widget_update( e, widget_el ) {
		//ready uploaders
		$('#widgets-right .wpg_media_upload').each(function(){
			$(this).wpg_uploader();
		});	//.each		
	}//wpg_widget_update
	
	wpg_widget_update();
	
	//ready uploaders for new and updated widgets
	$( document ).on( 'widget-updated', wpg_widget_update );
	$( document ).on( 'widget-added', wpg_widget_update );

});//doc ready	