var windowWidth = $(window).width();
var windowHeight = $(window).height();
var documentWidth = $(document).width();
var documentHeight = $(document).height();

function onm_window_parameters() {

    windowWidth = $(window).width();
    windowHeight = $(window).height();
    documentWidth = $(document).width();
    documentHeight = $(document).height();

};

function CelebSubmit() {

    var selectCelebrity = function () {
        $("#confirm-appeal-section").slideUp();
        var submitBoxes = $('.hotspot .img');
        $.each(submitBoxes, function (i, d) {
            var $this = $(this);
            if (!$this.hasClass('can-edit')) {
                $this.removeClass("selected");
            }
            var submitBoxId = $this.attr("data-submit-box");
            $("#" + submitBoxId).hide();
        });

        var currentSubmitBoxId = $(this).attr("data-submit-box");
        $("#" + currentSubmitBoxId).show();
        $(this).addClass("selected");
    };


    var editSubmission = function () {
        $("#confirm-appeal-section").slideUp();
        var submitBoxes = $('.hotspot .img');
        $.each(submitBoxes, function (i, d) {
            var $this = $(this);
            if (!$this.hasClass('can-edit')) {
                $this.removeClass("selected");
            }
            var submitBoxId = $this.attr("data-submit-box");
            $("#" + submitBoxId).hide();
        });

        var currentSubmitBoxId = $(this).parents('.img').eq(0).attr("data-submit-box");
        $("#" + currentSubmitBoxId).show();
    };

    var isAllAppealMade = function () {
        var submitBoxes = $('.hotspot .img');
        var counter = 0;
        $.each(submitBoxes, function (i, d) {
            var $this = $(this);
            if ($this.hasClass('can-edit')) {
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

    };

    $('.hotspot .img').on("click", selectCelebrity);
    $(".Celebrity-Comments-Container .big-btn-submit").on("click", submitAction);
    $('.hotspot .img .edit-tick a').on("click", editSubmission);


    $("#done-btn").on('click', function () {
        $("#makeAnotherAppeal").hide();
        $("#confirmAppeal").show();
    });

    $("#confirm-btn").on('click', function () {
        $("#confirm-appeal-section").slideUp();
    });

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

    var moderationTemplate = '<div class="content column">' +
        '<p>{{text}}</p>' +
        '</div>' +
        '<div class="action-updates under-moderation column">' +
        '<div class="img"><img src="images/under-moderation.png" alt="Under Moderation"></div> ' +
        'Under Moderation' +
        '</div>';


    var exports = {
        init: function () {
            $('.make-an-appeal').on('click', exports.makeAppeal);
        },

        makeAppeal: function () {
            var parentLI = $(this).parents('.entries-li').eq(0);
            var celeb = parentLI.attr('data-celeb');
            parentLI.find('.first-column').remove().end()
                .find('.second-column').remove().end()
                .find('.content').remove();
            var celebName = exports.getCelebName(celeb);
            var submitAppealHTML = submitAppealTemplate.replace('{{celebName}}', celebName);
            parentLI.append(submitAppealHTML);
            parentLI.find('.make-an-appeal-submit').bind('click', exports.submitAppeal);

        },

        submitAppeal: function () {
            var parentLI = $(this).parents('.entries-li').eq(0);
            var celeb = parentLI.attr('data-celeb');
            var value = parentLI.find('textarea').val();
            parentLI.find('.submit-appeal-box').remove();

            var appealHTML = confirmAppealTemplate.replace('{{AppealText}}', value);
            parentLI.append(appealHTML);

            parentLI.find('.confirm-appeal-text .col-full-8').bind('click', exports.editAppeal);
            parentLI.find('.confirm-btn').bind('click', exports.confirmAppeal);

        },

        editAppeal: function () {
            var parentLI = $(this).parents('.entries-li').eq(0);
            var celeb = parentLI.attr('data-celeb');
            var value = $(this).html();
            parentLI.find('.confirm-appeal-box').remove();
            var submitAppealHTML = submitAppealTemplate;
            parentLI.append(submitAppealHTML);
            parentLI.find('textarea').val(value);
            parentLI.find('.make-an-appeal-submit').bind('click', exports.submitAppeal)
        },

        confirmAppeal: function () {
            var parentLI = $(this).parents('.entries-li').eq(0);
            var celeb = parentLI.attr('data-celeb');
            var value = parentLI.find('.col-full-8').html();
            parentLI.find('.confirm-appeal-box').remove();

            var confirmationHTML = moderationTemplate.replace('{{text}}', value);
            parentLI.append(confirmationHTML);


        },

        getCelebName: function (celeb) {
            var celebName = '';
            if (celeb == 0) {
                celebName = "Rocky and Mayur";
            }

            else if (celeb == 1) {
                celebName = "Gaurav Kapur";
            }

            else if (celeb == 2) {
                celebName = "Anushka Dandekar";
            }
            return celebName;
        }


    };

    return exports;

};


$(document).ready(function (e) {
    $(".tabContents").hide();
    $(".tabContents:first").show();

    $(".tabContaier ul li a").click(function () {
        var activeTab = $(this).attr("href");
        var activeTabbg = $(this).parent().css("background-color");
        $(".tabContaier ul li a").removeClass("active");
        $(".hotspotTabs").css("background-color", activeTabbg);
        $(this).addClass("active");
        $(".tabContents").hide();
        $(activeTab).fadeIn();
        return false;
    });

    $(".tabContaier .teamBanner ul li a").click(function(){
        $(".tabContaier .teamBanner ul li a").next("div.arrowDown").hide();
        $(this).next("div.arrowDown").show();
        
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
    $('.button-prev').click(function (e) {
        prevImage = currentImage == firstImage ? lastImage : currentImage - 1;
        sliderContent.animate({ "left": -prevImage * sliderImageWidth });
        currentImage = prevImage;
        e.preventDefault();
    });

    $(".navigationIcon").click(function () {
        if (windowWidth < 700) {
            $(".mainmenu,.synApplink,.navUser").slideToggle('4000');
            $(".overlay").toggle().css({"width": documentWidth, "height": documentHeight});
        } else {
            $(".mainmenu,.synApplink").slideToggle('4000');
            $(".overlay").toggle().css({"width": documentWidth, "height": documentHeight});
        }
    });

    $(".playVideo").click(function () {
        videoUrl = $(this).attr("data-video-src");
        videoHeight = $(".mainBanner").height();

        $(".bannerContainer").hide();
        $(".videoContainer").html('<div class="closeVideo"><i>X</i></div><div><iframe width="100%" height="360" frameborder="0" allowfullscreen src="http://www.youtube.com/embed/' + videoUrl + '?autoplay=1"></iframe></div>').show();
    });

    $(document).on("click", ".closeVideo i", function () {
        $(".videoContainer").hide().empty();
        $(".bannerContainer").fadeIn();
    });

    $(window).resize(function () {
        onm_window_parameters();
        $(".mainmenu,.synApplink,.navUser,.overlay,.videoOverlay").hide();
		$(".videoOverlay").find("iframe").attr("src", "#");
    });

    $(".upload-block upload-img, .upload-block label").on("click", function () {
        $('#my-file').trigger("click");
    });

    $('#my-file').change(function () {
        $('#select_file').html($(this).val());
        alert($(this).val());
    });
	
	$("body").on("click", function() {
		$('.sort-view-entries ul li ul.subMenu').hide();
	});
	
    $('.sort-view-entries > ul > li').find(".subMenu").hide();
    $('.sort-view-entries > ul > li').on("click", function (event) {
        //$('.sort-view-entries > ul > li .subMenu').hide();
		$(".sort-view-entries ul li a").removeClass("active");
		$(this).find("a").addClass("active");
        var $divsubMenu = $(this).find(".subMenu");
        if ($divsubMenu.is(':visible')) {
            $divsubMenu.hide();
        } else {
            $('.sort-view-entries > ul > li .subMenu').hide();
            $divsubMenu.toggle();
        }
		event.stopPropagation();
    });

    $('.sort-view-entries > ul > li .subMenu').on("click", function (event) {
        event.stopPropagation();
    });
	
	

    $(".view-entries-blk .shareWith").hide();
    $(".view-entries-blk .share .shareIcon").on("click", function () {
        $(".view-entries-blk .actionLogin").hide();
        var $divshareWith = $(this).parent().next();
        if ($divshareWith.is(':visible')) {
            $divshareWith.hide();
        } else {
            $(".view-entries-blk .shareWith").hide();
            $divshareWith.toggle();
        }
    });

    $(".view-entries-blk .actionLogin").hide();
    $(".view-entries-blk .like a").on("click", function () {
        $(".view-entries-blk .shareWith").hide();
        var $divactionLogin = $(this).next();
        if ($divactionLogin.is(':visible')) {
            $divactionLogin.hide();
        } else {
            $(".view-entries-blk .actionLogin").hide();
            $divactionLogin.toggle();
        }
    });

    $(".view-entries-blk .playAudio").hide();
    $(".view-entries-blk .audio-bg .play").on("click", function () {
        $(this).hide().next().show();
        //var audioElement = $(this).next().find('player');
    });


    /* Celebs Page JS Starts Here */
    $(".celebs-our-content,.celebsVideo").hide();
    $('.celebs-action a i').on("click", function () {
        $(".celebsVideo").find("iframe").attr("src", "#");
        $(".celebsContainer").show();
        var activeDiv = $(this).attr("class");
        $(".celebs-our-celebs,.celebs-our-content,.celebsVideo").hide();
        $(".celebs-our-content." + activeDiv).show();
    });

    $(".celebs-our-content .play-video a").on("click", function () {
        var videoURL = $(this).attr("rel");
        if (windowWidth < 767) {
            $(this).parent().parent().next().show();
            $(this).parent().parent().find(".play-video").hide();
            $(this).parent().parent().find(".description").hide();
            $(this).parent().parent().find(".more").hide();
            $(this).parent().parent().next().find("iframe").attr("src", "http://www.youtube.com/embed/" + videoURL + "?autoplay=1");
        } else {
            $(this).parent().parent().next().show();
            $(this).parent().parent().hide().next().show();
            $(this).parent().parent().next().find("iframe").attr("src", "http://www.youtube.com/embed/" + videoURL + "?autoplay=1");
        }
    });

    $(".closeVideo i").on("click", function () {
        $(this).parent().next().attr("src", "#");
        $(this).parent().parent().hide();
        $(this).parent().parent().prev().show();
        if (windowWidth < 767) {
            $(this).parent().parent().prev().find(".play-video").show();
            $(this).parent().parent().prev().find(".description").show();
            $(this).parent().parent().prev().find(".more").show();
        } else {
            $(this).parent().parent().prev().show();
        }
    });
    /* Celebs Page JS Ends Here */
	
	
	$(".hotspots .hotspot .img a,.bannerTeam .playVideo a,#videoCarousel .item a").click(function(){
		var videoID = $(this).attr("rel");
		$(".overlay").toggle().css({"width": documentWidth, "height": documentHeight,"top": 0});
		$(".videoOverlay").show().find("iframe").attr("src", "http://www.youtube.com/embed/" + videoID + "?autoplay=1");
	});
	
	$(".closevideoOverlay i").on("click", function (){
		$(".overlay").hide();
		$(".videoOverlay").hide().find("iframe").attr("src", "");
	});
	
	 $("#videoCarousel").owlCarousel({
		//autoPlay : 3000,
		stopOnHover : true,
		items : 5,
		lazyLoad : true,
		navigation : true
		//transitionStyle : "fade"
	});
	
	/*$("#videoCarousel .item a").click(function () {
        videoUrl = $(this).attr("rel");
        videoHeight = $(".mainBanner").height();

        $(".bannerContainer").hide();
        $(".videoContainer").html('<div class="closeVideo"><i>X</i></div><div><iframe width="100%" height="340" frameborder="0" allowfullscreen src="http://www.youtube.com/embed/' + videoUrl + '?autoplay=1"></iframe></div>').show();
    });*/

});
