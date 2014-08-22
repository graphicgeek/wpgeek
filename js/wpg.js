jQuery(document).ready(function($){
	
	var wp_geek = {
		popupwidth: function(){
				return wpg_hook.filter('popupwidth', $(window).width() * .7);
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

	//hooking system
	var wpg_hook = {
		hooks:{},

		register:{
			filter:function(hook, callback){
				if(!wpg_hook.hooks[hook]){ wpg_hook.hooks[hook] = [];}

				wpg_hook.hooks[hook].push(callback);
			},
			action:function(hook, callback){
				if(!wpg_hook.hooks[hook]){ wpg_hook.hooks[hook] = [];}

				wpg_hook.hooks[hook].push(callback);
			}
		},//register

		filter:function(hook, val){

			if(!wpg_hook.hooks[hook]){return val;}

			var retval = val;
			var hooks = wpg_hook.hooks[hook];

			var l = hooks.length;

			for (var i=0;i<l; i++) {
				var callback = hooks[i];
				retval = callback(retval);
			}

			return retval;
		},//filter
		
		action:function(hook, args){
			if(!wpg_hook.hooks[hook]){return false;}

			var hooks = wpg_hook.hooks[hook];
			var l = hooks.length;

			for (var i=0;i<l; i++) {
				var callback = hooks[i];
				callback(args);
			}


		}//action

	};//wpg_hooks	
/*
	wpg_hook.register.filter('popupwidth', function(val){
		return 'test ' + val;
	});

	wpg_hook.register.filter('minor', function(val){
			return parseInt(val, 16)
	});


	console.log(wpg_hook.filter('popupwidth', 'filtered value'));
	
	console.log(wpg_hook.filter('minor', '29'));*/
});//doc ready