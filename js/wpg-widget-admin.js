jQuery(document).ready(function($){
	$('#widgets-right .wpg_media_upload').each(function(){
		$(this).wpg_uploader();
	});

	$('#widgets-right .widgets-sortables').on('sortstop',function(event,ui){   

		if($('#widgets-right .wpg_media_upload').size()>0){
			setTimeout(function(){
					$('#widgets-right .wpg_media_upload').each(function(){
						$(this).wpg_uploader();
					});
				},500);
		}
        
    });	
	
});//doc ready	