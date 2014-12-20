jQuery(document).ready(function($){
	
	$.fn.wpg_uploader = function(options){
		if(!this.hasClass('wpg_not_loaded')){ return;
		//this prevents wpg_uploader from being duplicated
		//once loaded, this class is removed
		 }		
		this.removeClass('wpg_not_loaded'); 
		
	
		if(!this.hasClass('wpg_media_upload')){ this.addClass('wpg_media_upload'); }
		if(!this.parent().hasClass('wpg_upload_field')){ this.wrap('<div class="wpg_upload_field"></div>'); }

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

		if(settings.allowMultiples){
			var gallery_template = $('#gallery_img_template li').clone();
			$('#gallery_img_template').remove();
			$('.sortable').sortable();
		}
		
		var file_frame;
		this.click(function(event){
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
					//var template = $('<div class="wpg_gallery_image"><input type="hidden" name="wpg[gallery_images][]" /></div>');
					selection.each(function(attachment){
						var tag = gallery_template.clone();
						console.log(tag);
						if(attachment.attributes.sizes.thumbnail){
							var url = attachment.attributes.sizes.thumbnail.url;
						} else {
							var url = attachment.attributes.sizes.full.url;
						}
						var result = '<img class="wpg_media_upload" src="' + url + '" />';
						tag.append(result);
						$('input', tag).val(attachment.attributes.id);
						$(settings.upload_result).append(tag); //display image							
					});	//selection.each			
				} else {
					//handle single image	
					attachment = file_frame.state().get('selection').first().toJSON();
					
					var result = '<img class="wpg_media_upload" src="' + attachment.sizes.thumbnail.url + '" />';
					$(settings.upload_result).html(result); //display image	
					$(settings.ID).val(attachment.id); //send id to input				
				}
	
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