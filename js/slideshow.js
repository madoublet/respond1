// begin slideshow widget
var slideshow = {

  current: 1,
  total: 1,
  offset: 0,

  init:function(){
	
	slideshow.offset = $('div.template').width();

	slideshow.total = $('div.template').length;
	
	var containerWidth = slideshow.offset*slideshow.total;
	
	$('span.previous').addClass('disabled');

	if(slideshow.total <= 1){
		$('span.next').addClass('disabled');
	}
	
	$('#slider').css({ width: containerWidth });
	
	$('span.next').live('click', function(){
		
		if($(this).hasClass('disabled')){return;}

		$('span.previous').removeClass('disabled');
		
      	var next = slideshow.current+1;
		
		left = (next-1) * (-1) * slideshow.offset;
		
		$('#slider').animate({
			marginLeft: left
		});
		
		slideshow.current++;
		
		if(slideshow.current==slideshow.total){
			$('span.next').addClass('disabled');
		}
		
		return false;
	});
	
	$('span.previous').live('click', function(){
		
		if($(this).hasClass('disabled')){return;}
		
		$('span.next').removeClass('disabled');
		
      	var next = slideshow.current-1;
		
		left = (next-1) * (-1) * slideshow.offset;
		
		$('#slider').animate({
			marginLeft: left
		});
		
		slideshow.current--;
		
		if(slideshow.current==1)
			{$('span.previous').addClass('disabled');
		}
		
		return false;
	});
	
  },
  
  // goes to the selected slide
  gotoSlide:function(slide){
	    
	var left = (slide-1) * (-1) * slideshow.offset;
	
	$('span.next').removeClass('disabled');
	$('span.previous').removeClass('disabled');
	
	$('#slider').css({marginLeft: left});
	
	slideshow.current = slide;
	
	if(slideshow.current==1)
		{$('span.previous').addClass('disabled');
	}
	
	if(slideshow.current==slideshow.total){
		$('span.next').addClass('disabled');
	}
  }

}

$(document).ready(function(){slideshow.init();});

