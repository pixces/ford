$(document).ready(function(e){
	$(".tabContents").hide(); 
	$(".tabContents:first").show();
	
	$(".tabContaier ul li a").click(function(){		
		var activeTab = $(this).attr("href");
		var activeTabbg = $(this).parent().css("background-color");
		$(".tabContaier ul li a").removeClass("active");
		$(".hotspotTabs").css("background-color",activeTabbg);
		$(this).addClass("active"); 
		$(".tabContents").hide(); 
		$(activeTab).fadeIn();		
		return false;
	});
	
   // find the position of the first image  
   var firstImage = $('.slider-content .slider-block:first').index();  
   // find the position of the last image  
   var lastImage = $('.slider-content .slider-block:last').index();  
  
   // set current, next and previous image  
   var currentImage = firstImage  
   var nextImage = firstImage + 1  
   var prevImage = lastImage  
  
   var sliderImages = $('.slider-content .slider-block');  
   var sliderContent = $('.slider-content');  
  
   // find the image width    
   var sliderImageWidth = parseFloat(sliderImages.eq(0).css('width'));  
  
   // when clicking the next button find out the next image position (nextImage)  
   // if currentImage == lastImage - your next image (nextImage) will grab the position of the firstImage  
   // otherwise nextImage = currentImage + 1  
  
   // calculate how much sliderContent will slide   
   // use animate function to slide  
   // set nextImage to be current image   
   $('.button-next').click(function(e) {  
       nextImage = currentImage == lastImage ? firstImage : currentImage + 1;  
       sliderContent.animate({ "left": -nextImage * sliderImageWidth });  
       currentImage = nextImage;  
       e.preventDefault();    
   });  
   $('.button-prev').click(function(e) {  
       prevImage = currentImage == firstImage ? lastImage : currentImage - 1;  
       sliderContent.animate({ "left": -prevImage * sliderImageWidth });  
       currentImage = prevImage;  
       e.preventDefault();    
   });
});