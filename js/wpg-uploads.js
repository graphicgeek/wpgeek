jQuery(document).ready(function($){
	
		$.fn.wpg_ready_uploads = function(){
		var file_frame;
		$('.wpg_media_upload').click(function(event){
			// Uploading files
			file_frame = '';
		
			event.preventDefault();
			
			var thisID = $(this).attr('ID');
			
			var imgID = '#' + thisID + '_img';
			var idID = '#' + thisID + '_id';
			var uploadType = $(this).data('uploadtype');
			if(uploadType == 'gallery_images'){var allowMultiples = true;}
			else {var allowMultiples = false;}
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}
	 
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: $( this ).data( 'uploader_title' ),
			button: {
				text: $( this ).data( 'uploader_button_text' ),
			},
			multiple: allowMultiples  // Set to true to allow multiple files to be selected
		});
	 
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
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
		
		$('.wpg_delete').click(function(){
			$(this).parent().remove();
		});//$('.wpg_delete').click
					
					} else {
						$('#' + thisID + '_upload_result').html(uploadType + ' URL: ' + attachment.url);
						//console.log('#' + thisID + '_upload_result');
					}
				
				}
				
			$(idID).val(attachment.id); //send id to input	
			
		  // Do something with attachment.id and/or attachment.url here
		});
	 
		// Finally, open the modal
		file_frame.open();
		});//$('.wpg_media_upload').click

		$('.wpg_delete').click(function(){
			$(this).parent().remove();
		});//$('.wpg_delete').click

	}//$.fn.wpg_ready_uploads

});//doc ready