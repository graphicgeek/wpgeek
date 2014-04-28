jQuery(document).ready(function($){
	
	$('.wpg_delete').parent().on('click', 'wpg_delete', function(){
		console.log('deleted');
		$(this).parent().html('');
	});//$('.wpg_delete').click

});//doc ready