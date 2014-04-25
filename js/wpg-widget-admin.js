jQuery(document).ready(function($){
	$(window).wpg_ready_uploads();

	$('div.widgets-sortables').on('sortstop',function(event,ui){   

		if($('.wpg_media_upload').size()>0){
			setTimeout(function(){$('.wpg_media_upload').wpg_ready_uploads()},500)
		}
        
    });	
	
});//doc ready	