(function($) {
	'use strict';
	$(document).ready(function() {
		/*-----------------------------------------------------------------------------------*/
		/*  Widget without title fix
		/*-----------------------------------------------------------------------------------*/ 
			$( '.widget' ).each(function() {
				if($(this).find('.widget-title').length === 0){
					$(this).addClass('withoutTitle');
				}
			});
		/*-----------------------------------------------------------------------------------*/
		/*  Home icon in main menu
		/*-----------------------------------------------------------------------------------*/ 
			if($('body').hasClass('rtl')) {
				$('.main-navigation .menu-item-home:first-child > a').append('<i class="fa fa-home spaceLeft"></i>');
			} else {
				$('.main-navigation .menu-item-home:first-child > a').prepend('<i class="fa fa-home spaceRight"></i>');
			}
		/*-----------------------------------------------------------------------------------*/
		/*  Detect touch screen device
		/*-----------------------------------------------------------------------------------*/ 
			var mobileDetect = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
		/*-----------------------------------------------------------------------------------*/
		/*  Posts Slider
		/*-----------------------------------------------------------------------------------*/ 
			if ($( '#mainslider' ).length ) {
				$('.fora_slider').each(function(index, element) {
					var sync1 = $('#owl-main-slide');
					var sync2 = $('#owl-post-nav-content');
					sync1.owlCarousel({
						singleItem : true,
						slideSpeed : 1000,
						pagination:false,
						afterAction : syncPosition,
						responsiveRefreshRate : 200
					});
					sync2.owlCarousel({
						items : 4,
						itemsDesktop      : [1199,4],
						itemsDesktopSmall     : [1024,2],
						itemsTablet       : [768,2],
						itemsMobile       : [700,1],
						pagination:false,
						responsiveRefreshRate : 100,
						afterInit : function(el){
						  el.find('.owl-item').eq(0).addClass('synced');
						}
					});
					function syncPosition(el){
						var current = this.currentItem;
						$('#owl-post-nav-content')
						  .find('.owl-item')
						  .removeClass('synced')
						  .eq(current)
						  .addClass('synced');
						if($('#owl-post-nav-content').data('owlCarousel') !== undefined){
						  center(current);
						}
					}
					$('#owl-post-nav-content').on('click', '.owl-item', function(e){
						e.preventDefault();
						var numberS = $(this).data('owlItem');
						sync1.trigger('owl.goTo',numberS);
					});
					$('.foraSliderCaption .inner-item .caption').on('click', 'a', function(e){
						e.preventDefault();
						var href= $(this).attr('href');
						window.location=href;
						return false;
					});
					function center(numberA){
						var sync2visible = sync2.data('owlCarousel').owl.visibleItems;
						var num = numberA;
						var found = false;
						for(var i in sync2visible){
						  if(num === sync2visible[i]){
							var found = true;
						  }
						}
						if(found===false){
						  if(num>sync2visible[sync2visible.length-1]){
							sync2.trigger('owl.goTo', num - sync2visible.length+2);
						  }else{
							if(num - 1 === -1){
							  num = 0;
							}
							sync2.trigger('owl.goTo', num);
						  }
						} else if(num === sync2visible[sync2visible.length-1]){
						  sync2.trigger('owl.goTo', sync2visible[1]);
						} else if(num === sync2visible[0]){
						  sync2.trigger('owl.goTo', num-1);
						}
					}
				});
			}
		/*-----------------------------------------------------------------------------------*/
		/*  Menu Widget
		/*-----------------------------------------------------------------------------------*/
			if ( $( 'aside ul.menu' ).length ) {
				$('aside ul.menu').find('li').each(function(){
					if($(this).children('ul').length > 0){
						$(this).append('<span class="indicatorBar"></span>');
					}
				});
				$('aside ul.menu > li.menu-item-has-children .indicatorBar, .aside ul.menu > li.page_item_has_children .indicatorBar').click(function() {
					$(this).parent().find('> ul.sub-menu, > ul.children').toggleClass('yesOpenBar');
					$(this).toggleClass('yesOpenBar');
					var $self = $(this).parent();
					if($self.find('> ul.sub-menu, > ul.children').hasClass('yesOpenBar')) {
						$self.find('> ul.sub-menu, > ul.children').slideDown(300);
					} else {
						$self.find('> ul.sub-menu, > ul.children').slideUp(200);
					}
				});
			}
		/*-----------------------------------------------------------------------------------*/
		/*  Mobile Menu
		/*-----------------------------------------------------------------------------------*/ 
			if ($( window ).width() <= 1025) {
				$('.main-navigation').find('li').each(function(){
					if($(this).children('ul').length > 0){
						$(this).append('<span class="indicator"></span>');
					}
				});
				$('.main-navigation ul > li.menu-item-has-children .indicator, .main-navigation ul > li.page_item_has_children .indicator').click(function() {
					$(this).parent().find('> ul.sub-menu, > ul.children').toggleClass('yesOpen');
					$(this).toggleClass('yesOpen');
					var $self = $(this).parent();
					if($self.find('> ul.sub-menu, > ul.children').hasClass('yesOpen')) {
						$self.find('> ul.sub-menu, > ul.children').slideDown(300);
					} else {
						$self.find('> ul.sub-menu, > ul.children').slideUp(200);
					}
				});
			}
			$(window).resize(function() {
				if ($( window ).width() > 1025) {
					$('.main-navigation ul > li.menu-item-has-children, .main-navigation ul > li.page_item_has_children').find('> ul.sub-menu, > ul.children').slideDown(300);
				}
			});
		/*-----------------------------------------------------------------------------------*/
		/*  Detect Mobile Browser
		/*-----------------------------------------------------------------------------------*/ 
			if (!mobileDetect) {
				/*-----------------------------------------------------------------------------------*/
				/*  Scroll To Top
				/*-----------------------------------------------------------------------------------*/ 
				$(window).scroll(function(){
					if ($(this).scrollTop() > 700) {
						$('#toTop').addClass('visible');
					} 
					else {
						$('#toTop').removeClass('visible');
					}
				}); 
				$('#toTop').click(function(){
					$('html, body').animate({ scrollTop: 0 }, 1000);
					return false;
				});
			}
	});
})(jQuery);