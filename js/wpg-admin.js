jQuery(document).ready(function($){
	
	$('div').on('click', '.wpg_delete', function(){
		$(this).parent().html('');
	});//$('.wpg_delete').click

	$('.wpg_field.datepicker').each(function(){
		$(this).datepicker({ dateFormat: date_format });
	});

});//doc ready