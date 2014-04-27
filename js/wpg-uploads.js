jQuery(document).ready(function($){
	
	$.fn.wpg_uploader = function(options){

        // default options.
        var settings = $.extend({
			allowMultiples: false,
			uploadType: 'image',
			uploader_title: 'Select an Image',
			uploader_button_text: 'Use Image',
			upload_result: '#' + $(this).attr('ID') + '_result',
			ID: '#' + $(this).attr('ID') + '_id',
			dataOverride: true
        }, options);
		
		if(settings.dataOverride){
			//override settings with data
			if($(this).data('multiples')){settings.allowMultiples = true}
			if($(this).data('uploadType')){settings.uploadType = $(this).data('uploadType')}
			if($(this).data('uploader_title')){settings.uploader_title = $(this).data('uploader_title')}
			if($(this).data('uploader_button_text')){settings.uploader_button_text = $(this).data('uploader_button_text')}
			if($(this).data('upload_result')){settings.upload_result = $(this).data('upload_result')}
		}
				
		console.log($(this).attr('ID'));
		var file_frame;
		$(this).click(function(event){
			// Uploading files
			file_frame = null;
		
			event.preventDefault();
	 
			// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: settings.uploader_title,
			button: {
				text: settings.uploader_button_text,
			},
			multiple: settings.allowMultiples  // Set to true to allow multiple files to be selected
		});
	 
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			
			if(settings.allowMultiples){
				//handle multiple selections
				var selection = file_frame.state().get('selection');
				selection.each(function(attachment){
					console.log(attachment.sizes.thumbnail.url);
					
				});	//selection.each			
			} else {
				//handle single image	
				attachment = file_frame.state().get('selection').first().toJSON();
				
				var result = '<img class="wpg_media_upload" src="' + attachment.sizes.thumbnail.url + '" />';
				console.log(settings.upload_result);
				$(settings.upload_result).html(result); //display image
				
				//console.log(attachment.sizes.thumbnail.url);
			}

/*			
			// We set multiple to false so only get one image from the uploader
			attachment = file_frame.state().get('selection').first().toJSON();
			
			if(!uploadType){
						
				$(imgID).attr('src', attachment.url); //display image
	
				}else{
					if(uploadType == 'gallery_images'){
    	var selection = file_frame.state().get('selection');
    	var totalItems = $('#wpg_gallery_total_img').val();
		selection.each(function(attachment){
    	var thisLI	
    	thisLI = '<li class="ui-state-default" id="sortable_li_' + attachment.id + '">';
		thisLI += '<img src="' + attachment.attributes.url + '" alt="" />';
		thisLI += '<input type="text" name="gallery_image_titles[]" value="' + attachment.attributes.title + '">';
		thisLI += '<input type="hidden" name="gallery_images[]" value="' + attachment.id + '">';
		thisLI += '<input type="hidden" name="gallery_image_type[]" value="image">';
		thisLI += '<span class="wpg_delete_li" data-ID="' + attachment.id + '">X</span>';
		thisLI += '</li>';
       
        $('#' + thisID + '_upload_result').append(thisLI);
    		totalItems++;
    		$('#wpg_gallery_total_img').val(totalItems);
    		
});//.each
		

					
					} else {
						$('#' + thisID + '_upload_result').html(uploadType + ' URL: ' + attachment.url);
						//console.log('#' + thisID + '_upload_result');
					}
				
				}
				
			$(idID).val(attachment.id); //send id to input	
			*/
		  // Do something with attachment.id and/or attachment.url here
			});//file_frame.on( 'select'
	 
		// Finally, open the modal
			file_frame.open();
		});//$('.wpg_media_upload').click

	}//$.fn.wpg_ready_uploads
	
	$('.wpg_media_upload').each(function(){
		if($(this).data('auto-initiate')){
			$(this).wpg_uploader();
		}
	});	

});//doc ready