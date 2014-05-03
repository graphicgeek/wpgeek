jQuery(document).ready(function($){
	
	$('div').on('click', '.wpg_delete', function(){
		$(this).parent().html('');
	});//$('.wpg_delete').click

});//doc ready