var windowWidth = $(window).width(); 
var windowHeight = $(window).height(); 
var documentWidth = $(document).width(); 
var documentHeight = $(document).height();

var pollInterval = 1500;
var maxTries = 50;
var tryCount = 1;
var SOCIALURL = SOCIAL_HOST;

function onm_window_parameters(){ 

	windowWidth = $(window).width(); 
	windowHeight = $(window).height(); 
	documentWidth = $(document).width(); 
	documentHeight = $(document).height(); 
	
};

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

	$(".navigationIcon").click(function(){
		if(windowWidth < 700){
			$(".mainmenu,.synApplink,.navUser").slideToggle('4000');
			$(".overlay").toggle().css({"width":documentWidth,"height":documentHeight});
		}else{
			$(".mainmenu,.synApplink").slideToggle('4000');
			$(".overlay").toggle().css({"width":documentWidth,"height":documentHeight});
		}
	});
	
	$(".playVideo").click(function(){
		videoUrl = $(this).attr("data-video-src");
		videoHeight = $(".mainBanner").height();
		
		$(".bannerContainer").hide();
		$(".videoContainer").html('<div class="closeVideo"><i>X</i></div><div><iframe width="100%" height="'+videoHeight+'" frameborder="0" allowfullscreen src="http://www.youtube.com/embed/'+videoUrl+'?autoplay=1"></iframe></div>').slideDown();		
	});
	
	$(document).on("click", ".closeVideo i",function(){
		$(".videoContainer").slideUp().empty();
		$(".bannerContainer").fadeIn();
	}).on("change", "#my-file", IMAGE.init);
	
	$(window).resize(function() {
		onm_window_parameters();
		$(".mainmenu,.synApplink,.navUser,.overlay").hide();
	});
	
	$(".upload-block upload-img, .upload-block label").on("click",function(){
		$('#my-file').trigger("click");
	});
	
	$('#my-file').change(function() {
		$('#select_file').html($(this).val());
		//alert($(this).val());
    });
});


var IMAGE = {

    widget :false,
    previewId :'preview',
    init : function(){
        if(IMAGE.widget){
            IMAGE.previewId = $(this).attr('preview');
        }
        var gallery_image = this;
        var val = $(this).val();
        var result = IMAGE.checkFileType(val);
        if (result) {
            IMAGE.previewImage(gallery_image);
        }
    },
    checkFileType: function(val) {
        switch (val.substring(val.lastIndexOf('.') + 1).toLowerCase()) {
            case 'gif':
            case 'jpg':
            case 'png':
            case 'jpeg':
                return true;
                break;
            default:
                $(this).val('');
                // error message here
                alert("not an image");
                return false;
                break;
        }
    },
    previewImage: function(image) {
        var $prev = $('#' + IMAGE.previewId);
        if (image.files && image.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $prev.attr('src', e.target.result);
            }

            reader.readAsDataURL(image.files[0]);

            $(".upload-block").addClass("user-image");
            $prev.removeClass('hide');
            $("#upload-label").text("CHANGE");
        } else {
            $prev.addClass('hide');
        }
    }
};


var jqxhr;
function getUserInfo(social) {
    //console.info(tryCount+" Try count | "+maxTries + " max tries");

    jqxhr = $.ajax({
        url: SOCIALURL + "UserInfo?media=" + social,
        type: "GET",
        cache: false,
        dataType: "jsonp"
        })
        .done(function(output) {
              console.info(output);
            if (output.response === "false") {
            } else {
                $("#social_signup").remove();

                socialUserInfo = output.info;
                var $uploadBlock = $(".upload-block"),
                    fullName = socialUserInfo.first_name+" "+socialUserInfo.last_name;

                $("#preview",$uploadBlock).attr('src',socialUserInfo.profile_photo).removeClass('hide');
                $("#name").val(fullName);
                $("#email").val(socialUserInfo.email_id);
                $("#city").val(socialUserInfo.city);
                $uploadBlock.addClass("user-image");
                $("#social_profile_pic").val(socialUserInfo.profile_photo);
                $("#authenticated_from").val(output.media);
                $("#upload-label").text("CHANGE");
                $(".register-socialIcon").fadeOut("slow",function(){
                    $(this).prev('.register-description').remove();
                    $(this).remove();
                    $(".register-description").text("Great We've got all your information");
                });

                if (output.media == "facebook" || output.media == "twitter") {
                    $("#" + output.media + "-url").val(socialUserInfo.username).attr("readonly", "readonly");
                    $("#" + output.media + "-check").attr("checked", "checked")
                }

                tryCount = maxTries;
            }

        })
        .fail(function() {
            //console.info('In fail action');
        })
        .always(function() {
            tryCount++;
            if (tryCount > 0 && tryCount < maxTries) {
                setTimeout(function() {
                    getUserInfo(social)
                }, pollInterval);
            } else {
                tryCount = 0;
            }
        });
}