(function($){
$(function(){
	var ypos = $('.right-menu').length + 20;

	var scrollFade = function(elem,elem2,pos,anim1,anim2,time){
	   var win=$(window);
	   win.on('scroll', function(){
	       if(win.scrollTop()>pos){
	           elem.stop().css('display', 'block').animate(anim1, time);
	       	   elem2.stop().css('display', 'none').animate(anim2, time);
	       	}
	       else{
	           elem.stop().css('display', 'none').animate(anim2, time);
	       	   elem2.stop().css('display', 'block').animate(anim1, time);
	       	}
	   });
	}

	scrollFade($('.aparecer'), $('.esconder'), ypos, {opacity: 1}, {opacity: 0}, 100);

	$('.tool').tooltip({
		placement: 'left'
	});

	$('#choose_cliente').modal();

	$('[data-toggle="popover"]').popover({
		html: true,
		content: function() {
          return $('#popoverExampleTwoHiddenContent').html();
        },
        title: function() {
          return $('#popoverExampleTwoHiddenTitle').html();
        }
	});

	 
});
})(jQuery);
