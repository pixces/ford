var windowWidth = $(window).width();
var windowHeight = $(window).height();
var documentWidth = $(document).width();
var documentHeight = $(document).height();

var pollInterval = 1500;
var maxTries = 50;
var tryCount = 1;
var SOCIALURL = SOCIAL_HOST;
var SITEURL = SITE_URL;
var ENV = 'base';
var LANG = 'en';
var PHASE = 'recruit';

function onm_window_parameters(){ 

    windowWidth = $(window).width();
    windowHeight = $(window).height();
    documentWidth = $(document).width();
    documentHeight = $(document).height();

};

function CelebSubmit() {

    var selectCelebrity = function(){
        $("#confirm-appeal-section").slideUp();
        var submitBoxes = $('.hotspot .img');
        $.each(submitBoxes, function(i, d){
            var $this = $(this);
            if (!$this.hasClass('can-edit')){
                $this.removeClass("selected");
            }
            var submitBoxId = $this.attr("data-submit-box");
            $("#" + submitBoxId).hide();
        });

        var currentSubmitBoxId = $(this).attr("data-submit-box");
        $("#" + currentSubmitBoxId).show();
        $(this).addClass("selected");
    };

    var editSubmission = function(){
        $("#confirm-appeal-section").slideUp();
        var submitBoxes = $('.hotspot .img');
        $.each(submitBoxes, function(i,d){
            var $this = $(this);
            if(!$this.hasClass('can-edit')){
                $this.removeClass("selected");
            }
            var submitBoxId = $this.attr("data-submit-box");
            $("#" + submitBoxId).hide();
        });

        var currentSubmitBoxId = $(this).parents('.img').eq(0).attr("data-submit-box");
        $("#" + currentSubmitBoxId).show();
    };

    var isAllAppealMade = function(){
        var submitBoxes = $('.hotspot .img');
        var counter = 0;
        $.each(submitBoxes, function(i,d){
            var $this = $(this);
            if($this.hasClass('can-edit')){
                counter++;
            }
        });

        return counter;
    };


    var submitAction = function () {
        var $this = $(this);
        var submitFor = $this.attr("data-submit-for");
        var $submitFor = $('#' + submitFor)
        var celebIcon = $this.attr("data-celeb-icon");
        var $celebIcon = $("#" + celebIcon);

        //make a call to post the data
        var channel = $this.attr("data-celeb");
        var comment = $("#" + channel + "-comment").val();
        var user_id = $this.attr("data-user");
        var is_ugc = 1;


        //make the call to save this data
        $.ajax({
            type: "POST",
            cache: false,
            url: SITEURL + "/ugc/" + "save",
            data: {user_id: user_id, channel: channel, comment: comment, is_ugc: is_ugc},
            dataType: "json",
            success: function (data) {
                if (data.response === 'false') {
                    //$(".subscribe-message").text(data.message);
                    // do something with error;
                    console.log(data.message);
                } else {
                    console.log(data.message);

                    //do all the success actions here
                    $submitFor.addClass("can-edit").find(".make-an-appeal-txt").hide();
                    $submitFor.unbind('click', selectCelebrity);

                    $celebIcon.addClass('active');
                    $this.parents('.Celebrity-Comments-Container').eq(0).hide();

                    var isAllAppeal = isAllAppealMade();

                    if (isAllAppeal == 3) {
                        $("#makeAnotherAppeal").hide();
                        $("#confirmAppeal").show();
                    } else {
                        $("#makeAnotherAppeal").show();
                        $("#confirmAppeal").hide();
                    }

                    $("#confirm-appeal-section").slideDown();

                    //$(".subscribe-message").text('for subscribing to our newsletter');
                }
            },
            error: function () {
                console.info("request error please debug");
                console.info(SITEURL + "/ugc/" + "save");
                console.info({user_id: user_id, channel: channel, comment: comment, is_ugc: is_ugc});
            }
        });
    };


     var submitConfirm = function (e) {

         e.preventDefault;

         var $this = $(this);
         var btnId = $this.attr("id");
         var user_id = $this.attr("data-user");
         var is_ugc = 1;
         //var location = $this.attr('href');

         console.log(btnId);
         console.log(user_id);
         console.log(is_ugc);


         //make the call to save this data
         $.ajax({
             type: "POST",
             cache: false,
             url: SITEURL + "/ugc/" + "confirm",
             data: {user_id: user_id, is_ugc: is_ugc},
             dataType: "json",
             success: function (data) {
                 if(data.response === 'success'){
                     //redirect to the profile page now
                     console.log(data.message);
                     var location = SITEURL+"/user/profile/"+"?env="+ENV+"&lang="+LANG+"&phase="+PHASE;
                     console.log(location);
                     window.location.href = location;
                 } else {
                    console.info("error" + data.message);
                     return false;
                 }
            },
            error: function () {
                console.info("request error please debug");
                console.info(SITEURL + "/ugc/" + "confirm");
                console.info({user_id: user_id, is_ugc: is_ugc});
                return false;
            }
         });
     };

    $('.hotspot .img').on("click", selectCelebrity);
    $(".Celebrity-Comments-Container .big-btn-submit").on("click", submitAction);
    $('.hotspot .img .edit-tick a').on("click", editSubmission);

    //confirm submission on click of either of these buttons
    $("#done-btn").on('click', submitConfirm);
    $("#confirm-btn").on('click', submitConfirm);

    /*$("#done-btn").on('click', function(){

     $("#makeAnotherAppeal").hide();
     $("#confirmAppeal").show();

     submitConfirm;

     });

     $("#confirm-btn").on('click', function(){

     //make a call to update the is_submitted = true
     //for all the posted comments
     submitConfirm
     $("#confirm-appeal-section").slideUp();
     });*/



};


var ProfileSubmitAppeal = function () {
    var submitAppealTemplate = '<div class="content two-third-ct column submit-appeal-box">' +
        '<textarea class="make-appeal-text-area">Make an appeal to {{celebName}}</textarea>' +
        '<div class="btn-ct"><div class="arrow-right"></div>' +
        '<button class="button orange make-an-appeal-submit">Submit</button></div>' +
        '</div>';

    var confirmAppealTemplate = '<div class="content two-third-ct column confirm-appeal-box">' +
        '<div class="confirm-appeal-text">' +
        '<div class="col-full-8">{{AppealText}}</div>' +
        '<div class="col-full-2">' +
        '<div class="btn-ct">' +
        '<div class="arrow-right"></div>' +
        '<p>ONCE YOU SUBMIT YOUR ENTRY YOU CANNOT CHANGE IT.</p>' +
        '<button class="button orange confirm-btn">Confirm</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';

    var moderationTemplate = '<div class="content two-third-ct column {{celebName}}-appeal-content-area">' +
        '<div class="content column">' +
        '<p>{{text}}</p>' +
        '</div>' +
        '<div class="action-updates under-moderation column">' +
        '<div class="img">' +
        '<img src="'+SITEURL+'/images/under-moderation.png" alt="Under Moderation">' +
        '</div>' +
        'Under Moderation' +
        '</div>' +
        '</div>';

    var exports = {
        init: function(){
            $('.make-an-appeal').on('click', exports.makeAppeal);
            $('.confirm-appeal-text .col-full-8').on('click', exports.editAppeal);
            $('.confirm-btn').on('click', exports.confirmAppeal);
        },

        makeAppeal: function(){
            var parentLI = $(this).parents('.entries-li').eq(0);
            var celeb = parentLI.attr('data-celeb');
            parentLI.find('.first-column').remove().end()
                .find('.second-column').remove().end()
                .find('.content').remove();
            var celebName = exports.getCelebName(celeb);
            var submitAppealHTML = submitAppealTemplate.replace('{{celebName}}', celeb);
            parentLI.append(submitAppealHTML);
            parentLI.find('.make-an-appeal-submit').bind('click', exports.submitAppeal);

        },

        submitAppeal: function(){

            var parentLI = $(this).parents('.entries-li').eq(0);
            var celeb = parentLI.attr('data-celeb');
            var user_id = parentLI.attr('data-user');
            var value = parentLI.find('textarea').val();

            //make a call to post the data
            var channel = celeb;
            var comment = value;
            var user_id = user_id;
            var is_ugc = 1;

            //make the call to save this data
            $.ajax({
                type: "POST",
                cache: false,
                url: SITEURL + "/ugc/" + "save",
                data: {user_id: user_id, channel: channel, comment: comment, is_ugc: is_ugc },
                dataType: "json",
                success: function (data) {
                    if (data.response === 'false') {
                        return false;
                        console.log('unable to save user submission');
                        console.log(data.message);
                    } else {
                        console.log(data.message);

                        //display the submission confirm box
                        parentLI.find('.submit-appeal-box').remove();

                        var appealHTML = confirmAppealTemplate.replace('{{AppealText}}', value);
                        parentLI.append(appealHTML);
                        parentLI.find('.confirm-appeal-text .col-full-8').bind('click', exports.editAppeal);
                        parentLI.find('.confirm-btn').bind('click', exports.confirmAppeal);
                    }
                },
                error: function () {
                    console.log("request error please debug");
                    console.log(SITEURL + "/ugc/" + "save");
                    console.log({user_id: user_id, channel: channel, comment: comment, is_ugc: is_ugc});
                }
            });
        },

        editAppeal: function(){
            var parentLI = $(this).parents('.entries-li').eq(0);
            var celeb = parentLI.attr('data-celeb');
            var value = $(this).html();
            parentLI.find('.confirm-appeal-box').remove();
            var submitAppealHTML = submitAppealTemplate;
            parentLI.append(submitAppealHTML);
            parentLI.find('textarea').val(value);
            parentLI.find('.make-an-appeal-submit').bind('click', exports.submitAppeal)
        },

        confirmAppeal: function(){
            var parentLI = $(this).parents('.entries-li').eq(0);
            var celeb = parentLI.attr('data-celeb');
            var value = parentLI.find('.col-full-8').html();
            var user_id = parentLI.attr('data-user');
            var is_ugc = 1;

            //make the call to save this data
            $.ajax({
                type: "POST",
                cache: false,
                url: SITEURL + "/ugc/" + "confirm",
                data: {user_id: user_id, channel: celeb, comment: value, is_ugc: is_ugc, confirm_type:'one' },
                dataType: "json",
                success: function (data) {
                    if (data.response === 'error') {
                        return false;
                        console.log('unable to save user submission');
                        console.log(data.message);
                    } else {
                        parentLI.find('.confirm-appeal-box').remove();
                        var confirmationHTML = moderationTemplate.replace(/{{celebName}}/g, celeb).replace(/{{text}}/g, value);
                        parentLI.append(confirmationHTML);
                    }
                },
                error: function () {
                    console.log("request error please debug");
                    console.log(SITEURL + "/ugc/" + "confirm");
                    console.log({user_id: user_id, channel: celeb, comment: value, is_ugc: is_ugc, confirm_type:'one' });
                }
            });
        },

        getCelebName: function(celeb){
            var celebName = '';
            if(celeb == 0){
                celebName = "Rocky and Mayur";
            }

            else if(celeb == 1){
                celebName = "Gaurav Kapur";
            }

            else if(celeb == 2){
                celebName = "Anushka Dandekar";
            }
            return celebName;
        }


    };

    return exports;

};


$(document).ready(function(e){
	$(document).on("focus", "textarea", function() {
        if (this.value === this.defaultValue) {
            this.value = '';
        }
	}).on("blur", "textarea", function() {
		if (this.value === '') {
			this.value = this.defaultValue;
		}
	});
	
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
    $('.button-next').click(function (e) {
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
            $(".overlay").toggle().css({"width": documentWidth, "height": documentHeight});
        }else{
            $(".mainmenu,.synApplink").slideToggle('4000');
            $(".overlay").toggle().css({"width": documentWidth, "height": documentHeight});
        }
    });

    $(".playVideo").click(function(){
        videoUrl = $(this).attr("data-video-src");
        videoHeight = $(".mainBanner").height();

        $(".bannerContainer").hide();
        $(".videoContainer").html('<div class="closeVideo"><i>X</i></div><div><iframe width="100%" height="360" frameborder="0" allowfullscreen src="http://www.youtube.com/embed/' + videoUrl + '?autoplay=1"></iframe></div>').show();
    });

    $(document).on("click", ".closeVideo i",function(){
        $(".videoContainer").hide().empty();
        $(".bannerContainer").fadeIn();
    }).on("change", "#my-file", IMAGE.init);

    $(window).resize(function() {
        onm_window_parameters();
        $(".mainmenu,.synApplink,.navUser,.overlay,.videoOverlay").hide();
		$(".videoOverlay").find("iframe").attr("src", "#");
    });

    $(".upload-block upload-img, .upload-block label").on("click",function(){
        $('#my-file').trigger("click");
    });

    $('#my-file').change(function() {
        $('#select_file').html($(this).val());
        //alert($(this).val());
    });

    //on select of the make-appeal button on the profile page
    $(".action-updates .make-an-appeal").on('click', function () {
        var celebName = $(this).attr('data-name');
        var color = $(this).attr('data-color');
        var form = $("#appeal-form").clone().attr("id", celebName + 'appeal-form');
        //find and replace text values and data_name
        form.find('.make-appeal-text-area').attr('placeholder', 'Make an appeal to ' + celebName);
        form.find('.make-appeal-text-area').attr('data-name', celebName);
        form.find('.make-an-appeal-submit').attr('data-name', celebName);
        form.find('.make-an-appeal-submit').addClass(color);
        form.find('.make-an-appeal-submit').addClass('submit-appeal');
        form.find('.appeal-form').attr("id", 'AppealFrm');

        //append this to the container
        $('.' + celebName + '-appeal-content-area').html(form);
    });

    var cities = [
        "Kolkata",
        "Chandigarh",
        "Delhi and NCR",
        "Ludhiana",
        "Jaipur",
        "Hyderabad",
        "Bangalore",
        "Cochin",
        "Chennai",
        "Ahmedabad",
        "Pune",
        "Mumbai",
        "Kathmandu ( New)"
    ];
    // setup autocomplete function pulling from cities[] array
    $('#autocomplete').autocomplete({
        lookup: cities
    });

    $('.sort-view-entries > ul > li').find(".subMenu").hide();
    $('.sort-view-entries > ul > li').on("click",function(event){
		//$('.sort-view-entries > ul > li .subMenu').hide();
		$(".sort-view-entries ul li a").removeClass("active");
		$(this).find("a").addClass("active");
		var $divsubMenu = $(this).find(".subMenu");
		if ($divsubMenu.is(':visible')){
			$divsubMenu.hide();
		}else{
			$('.sort-view-entries > ul > li .subMenu').hide();
			$divsubMenu.toggle();
		}
		event.stopPropagation();
	});
	
	$('.sort-view-entries > ul > li .subMenu').on("click",function(event){
		event.stopPropagation();
	});
	
	$("body").on("click", function() {
		$('.sort-view-entries ul li ul.subMenu').hide();
	});
	
	$(".view-entries-blk .shareWith").hide();
	$(".view-entries-blk .share .shareIcon").on("click",function(){
		$(".view-entries-blk .actionLogin").hide();
		var $divshareWith = $(this).parent().next();
		if ($divshareWith.is(':visible')){
			$divshareWith.hide();
		}else{
			$(".view-entries-blk .shareWith").hide();
			$divshareWith.toggle();
		}
	});

	$(".view-entries-blk .actionLogin").hide();
	$(".view-entries-blk .like a").on("click",function(){
		$(".view-entries-blk .shareWith").hide();
		var $divactionLogin = $(this).next();
		if ($divactionLogin.is(':visible')){
			$divactionLogin.hide();	
		}else{
			$(".view-entries-blk .actionLogin").hide();
			$divactionLogin.toggle();
		}
	});
	
	$(".view-entries-blk .playAudio").hide();
	$(".view-entries-blk .audio-bg .play").on("click",function(){
		$(this).hide().next().show();
		//var audioElement = $(this).next().find('player');
	});

/* Celebs Page JS Starts Here */
	$(".celebs-our-content,.celebsVideo").hide();
	$('.celebs-action a i').on("click",function(){
		$(".celebsVideo").find("iframe").attr("src","");
		$(".celebsContainer").show();
		var activeDiv = $(this).attr("class");
		$(".celebs-our-celebs,.celebs-our-content,.celebsVideo").hide();		
		$(".celebs-our-content."+activeDiv).show();
	});
	
	$(".celebs-our-content .play-video a").on("click",function(){
		var videoURL = $(this).attr("rel");
		if(windowWidth < 767){
			$(this).parent().parent().next().show();
			$(this).parent().parent().find(".play-video").hide();
			$(this).parent().parent().find(".description").hide();
			$(this).parent().parent().find(".more").hide();
			$(this).parent().parent().next().find("iframe").attr("src","http://www.youtube.com/embed/"+videoURL+"?autoplay=1");
	}else{
			$(this).parent().parent().next().show();
			$(this).parent().parent().hide().next().show();
			$(this).parent().parent().next().find("iframe").attr("src","http://www.youtube.com/embed/"+videoURL+"?autoplay=1");
		}
	});
	
	$(".closeVideo i").on("click",function(){
		$(this).parent().next().attr("src","");
		$(this).parent().parent().hide();
		$(this).parent().parent().prev().show();
		if(windowWidth < 767){
			$(this).parent().parent().prev().find(".play-video").show();
			$(this).parent().parent().prev().find(".description").show();
			$(this).parent().parent().prev().find(".more").show();	
	}else{			
			$(this).parent().parent().prev().show();
		}
	});
	/* Celebs Page JS Ends Here */
	
	$(".hotspots .hotspot .img a.playCelebVideo , .howitworksPlay").click(function(){
		var videoID = $(this).attr("rel");
		$(".overlay").toggle().css({"width": documentWidth, "height": documentHeight,"top": 0});
		$(".videoOverlay").show().find("iframe").attr("src", "http://www.youtube.com/embed/" + videoID + "?autoplay=1");
	});
	
	$(".closevideoOverlay i").on("click", function (){
		$(".overlay").hide();
		$(".videoOverlay").hide().find("iframe").attr("src", "");
	});
	
	/*$("#videoCarousel").owlCarousel({
		//autoPlay : 3000,
		stopOnHover : true,
		items : 5,
		lazyLoad : true,
		navigation : true
		//transitionStyle : "fade"
	});*/
	
	$("#videoCarousel .item a").click(function () {
        videoUrl = $(this).attr("rel");
        videoHeight = $(".mainBanner").height();

        $(".bannerContainer").hide();
        $(".videoContainer").html('<div class="closeVideo"><i>X</i></div><div><iframe width="100%" height="340" frameborder="0" allowfullscreen src="http://www.youtube.com/embed/' + videoUrl + '?autoplay=1"></iframe></div>').show();
    });
	
});


var IMAGE = {

    widget: false,
    previewId: 'preview',
    init: function () {
        if (IMAGE.widget) {
            IMAGE.previewId = $(this).attr('preview');
        }
        var gallery_image = this;
        var val = $(this).val();
        var result = IMAGE.checkFileType(val);
        if (result) {
            IMAGE.previewImage(gallery_image);
        }
    },
    checkFileType: function (val) {
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
    previewImage: function (image) {
        var $prev = $('#' + IMAGE.previewId);
        if (image.files && image.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
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
        .done(function (output) {
            console.info(output);
            if (output.response === "false") {
            } else {
                $("#social_signup").remove();

                socialUserInfo = output.info;
                var $uploadBlock = $(".upload-block"),
                    fullName = socialUserInfo.first_name + " " + socialUserInfo.last_name;

                $("#preview", $uploadBlock).attr('src', socialUserInfo.profile_photo).removeClass('hide');
                $("#name").val(fullName);
                $("#email").val(socialUserInfo.email_id);
                $("#city").val(socialUserInfo.city);
                $uploadBlock.addClass("user-image");
                $("#social_profile_pic").val(socialUserInfo.profile_photo);
                $("#authenticated_from").val(output.media);
                $("#upload-label").text("CHANGE");
                $(".register-socialIcon").fadeOut("slow", function () {
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
        .fail(function () {
            //console.info('In fail action');
        })
        .always(function () {
            tryCount++;
            if (tryCount > 0 && tryCount < maxTries) {
                setTimeout(function () {
                    getUserInfo(social)
                }, pollInterval);
            } else {
                tryCount = 0;
            }
        });
};

var GALLERY = (function () {

    var $galleryPlaceHolder = $("#entries");

    var Data = {
        Gallery: (typeof galleryData !== 'undefined') ? galleryData : "",
        aCelebrity : (typeof aCelebs !== 'undefined') ? aCelebs : "",
        offset : 0,
        limit : 15
    };

    var galleryTemplate = {
        text: [
            '<div class="entries-list {{CELEB}} transition">',
                '<div class="user-details">',
                    '<div class="user-pic"><img src="{{USER_IMAGE}}" /> <span>{{USER_NAME}}</span></div>',
                    '<div class="mid-icon"><i></i></div>',
                    '<div class="celebrity-type"><i></i> <span>{{CELEB_NAME}}</span></div>',
                '</div>',
                '<div class="user-description">{{CONTENT}}</div>',
                '<div class="action">',
                    '<div class="like">',
                        '<a href="javascript:void(0)"><i class="likeIcon"></i> {{VOTE}}</a>',
                        '<div class="actionLogin">',
                            '<div class="row no-margin">',
                                '<div class="field">',
                                    '<input type="text" placeholder="Login" id="login" name="login">',
                                    '<input type="password" placeholder="Password" id="password" name="password">',
                                    '<input type="submit" value="Login" id="submit" name="submit">',
                                '</div>',
                            '</div>',
                        '</div>',
                    '</div>',
                    '<div class="share">',
                        '<a href="javascript:void(0)"><i class="shareIcon"></i></a>',
                        '<div class="shareWith">',
                            '<ul>',
                                '<li><a href="javascript:void(0)"><i class="facebook"></i></a></li>',
                                '<li><a href="javascript:void(0)"><i class="twitter"></i></a></li>',
                                '<li><a href="javascript:void(0)"><i class="tube"></i></a></li>',
                            '</ul>',
                        '</div>',
                    '</div>',
                '</div>',
            '</div>'
        ].join(""),

        audio: [
            '<div class="entries-list anushka-dandekar transition">',
                '<div class="user-details">',
                    '<div class="user-pic"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user-small-icon.png" /> <span>Tarun Jaiswal</span></div>',
                    '<div class="mid-icon"><i></i></div>',
                    '<div class="celebrity-type"><i></i> <span>Anushka Dandekar</span></div>',
                '</div>',
                '<div class="user-description audio-bg">',
                    '<i class="play"></i>',
                    '<div class="playAudio">',
                        '<audio controls>',
                            '<source src="images/horse.mp3" type="audio/mpeg">',
                            '<source src="images/horse.ogg" type="audio/ogg">',
                            '<embed height="50" width="100" src="horse.mp3">',
                            'Your browser does not support this audio format.',
                        '</audio>',
                    '</div>',
                '</div>',
                '<div class="action">',
                    '<div class="like">',
                        '<a href="javascript:void(0)"><i class="likeIcon"></i> 21</a>',
                        '<div class="actionLogin">',
                            '<div class="row no-margin">',
                                '<div class="field">',
                                    '<input type="text" placeholder="Login" id="login" name="login">',
                                    '<input type="password" placeholder="Password" id="password" name="password">',
                                    '<input type="submit" value="Login" id="submit" name="submit">',
                                '</div>',
                            '</div>',
                        '</div>',
                    '</div>',
                    '<div class="share">',
                        '<a href="javascript:void(0)"><i class="shareIcon"></i></a>',
                        '<div class="shareWith">',
                            '<ul>',
                                '<li><a href="javascript:void(0)"><i class="facebook"></i></a></li>',
                                '<li><a href="javascript:void(0)"><i class="twitter"></i></a></li>',
                                '<li><a href="javascript:void(0)"><i class="tube"></i></a></li>',
                            '</ul>',
                        '</div>',
                    '</div>',
                '</div>',
            '</div>'
        ].join("")
    };

    var exports = {

        initialize: function(){
            GALLERY.buildGallery();
        },

        buildGallery : function( data ){

            if( data !== undefined ) {
                Gallery = data;
            } else {
                Gallery = Data.Gallery;
            }

            if (Gallery === null || !Gallery) { return; }
            var Output = [];
            var dataLength = Gallery.length;

            for(var i = dataLength-1; i >= 0; i--){
                var content = Gallery[i],
                    mediaType = content["content"]["type"],
                    user_image = (content['user']['profile_image'] === null || !content['user']['profile_image']) ? SITEURL+'/images/user-icon.png' : content['user']['profile_image'],
                    vote = content['content']['vote'],
                    celeb_name = '';

                var template = galleryTemplate[ mediaType ].replace( /{{CELEB}}/g, content['content']['channel_name'] )
                    .replace( /{{USER_IMAGE}}/g, user_image )
                    .replace( /{{USER_NAME}}/g, content['user']['full_name'] )
                    .replace( /{{CELEB_NAME}}/g, Data.aCelebrity[ content['content']['channel_name'] ]['name'])
                    .replace( /{{CONTENT}}/g, content['content']['description'] )
                    .replace( /{{VOTE}}/g, vote);

                Output.unshift( template );
            }

            $galleryPlaceHolder.append( Output.join("") );
        }


    };
    return exports;


})();

var SUBMISSION = (function () {


})();
