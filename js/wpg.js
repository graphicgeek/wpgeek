jQuery(document).ready(function($){
	
	var wp_geek = {
		popupwidth: function(){
				return $(window).width() * .7;
			},//popupwidth
			
		popupheight: function(){
				return $(window).height() * .8;
			},//popupheight
		
		init:function(){
			$('.wpg_lightbox').each(function(){
				$(this).colorbox();	
			});//.each

			$('.wpg_video_lightbox').each(function(){
				$(this).colorbox({
					iframe:true, 
					innerWidth:wp_geek.popupwidth(),
					innerHeight:wp_geek.popupheight()
				});
			});//.each
				
		}//init
		
	}//wp_geek
	
	wp_geek.init();
	


});//doc ready