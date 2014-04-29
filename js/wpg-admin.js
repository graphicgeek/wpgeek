jQuery(document).ready(function($){
	
	$('div').on('click', 'wpg_delete', function(){
		console.log('deleted');
		$(this).html('');
	});//$('.wpg_delete').click

});//doc ready