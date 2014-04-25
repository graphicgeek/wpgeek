jQuery(document).ready(function($){
	$('#widgets-right .wpg_media_upload').each(function(){
		$(this).wpg_uploader();
	});
	
		if($('#widgets-right .wpg_media_upload').length){
			$('#widgets-right .wpg_media_upload').wpg_uploader();
		}

	$('div.widgets-sortables').on('drop',function(event,ui){   

		if($('#widgets-right .wpg_media_upload').size()>0){
			setTimeout(function(){
					$('.wpg_media_upload').each(function(){
						$(this).wpg_uploader();
					});
				},500);
		}
        
    });	
	
});//doc ready	