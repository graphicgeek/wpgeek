jQuery(document).ready(function($){
	
	$('.wpg_delete').on('click', function(){
		$(this).parent().remove();
	});//$('.wpg_delete').click

});//doc ready