$(window).load(function() { 
	$(".page-loader").fadeOut("slow"); 
	$('body').removeClass('loading-now'); 
});
var processing = 'false';
function loadingGifblockUI(){
$.blockUI({ message: '<img style="width:200px;" src="frontend/images/loading.svg" />' });
}

$(document).ready(function(){
    $('#reg_type').change(function(){
        if($(this).val() == '3'){
            $('#reg_agency_name').removeClass('hide').addClass('show');
        }else{
            $('#reg_agency_name').removeClass('show').addClass('hide');
        }
    });
    
var validator = $("#formlogin").validate({
    errorContainer: $('#signinmsg'),
    errorPlacement: function(error, element) { },
    meta: "validate",
	//onfocusout: false,
	 invalidHandler: function() {
		$('.invalidCredentialsMsg').hide();
	  },
    rules: {
        login_password: {
            required: true,

        },
        login_email: {
            required: true,
            email: true
        }
    },
    submitHandler: function(form) {
        var password = $("#login_password").val();
        var email_address = $("#login_email").val();

        var param = "password="+password+"&email_address="+email_address+"&name="+name;
        $.ajax({
            type: "POST",
            url: baseurl+"/index/signin",
            data: param,
            success: function(msg){
                var obj = $.parseJSON(msg);
                if(obj.success){
                    if(obj.type=='1'){ //alert('Buyer');
                        window.parent.location = baseurl+"/profile";
                    }
                    if(obj.type=='2'){ //alert('Agent');
                        window.parent.location = baseurl+"/profile";
                    }
                    if(obj.type=='3'){ //alert('Agency');
                        window.parent.location = baseurl+"/profile/agency";
                    }
                }else{
                    $('.invalidCredentialsMsg').show();
                }
                
                $('#formlogin')[0].reset();

            },
            beforeSend: function(){
            },
            complete: function(){
            }
        });
    }
});
});

$(document).ready(function() {
var validationmssg = $(".validationmssg");
//$("#form").validate({
    var from = 'signup';
    var validator = $("#signup").validate({
        errorPlacement: function(error, element) { },
        errorContainer: validationmssg ,
        //meta: "validate",
        rules: {
            firstname: {
                required: true
                //,notEqual: "Name"
            },
            lastname: {
                required: true
                //,notEqual: "Name"
            },

            reg_password: {
                required: true,
                rangelength: [8, 12]

            },
            cpassword: {
                required: true,
                rangelength: [8, 12],
                equalTo: "#reg_password"
            },

            reg_email_address: {
                required: true,
                email: true
                //,remote: baseurl+"/index/checkuseremail/?from="+from
            }
        },
        submitHandler: function(form) {
            var first_name = $("#firstname").val();
            var last_name = $("#lastname").val();
            var password = $("#reg_password").val();
            var email_address = $("#reg_email_address").val();
            var agency_name = $("#reg_agency_name").val();
            var reg_type = $("#reg_type").val();
            var token = $('#token').val();
            //var param = "password="+passowrd+"&email_address="+email_address+"&username="+username;
            var param = "_token="+token+"&type=frontend&password="+password+"&email="+email_address+"&first_name="+first_name+"&last_name="+last_name+"&role_id="+reg_type+"&agency_name="+agency_name;
            jQuery.ajax({
                type: "POST",
                url:baseurl+'/login-register/signup',
                data: param,
                success: function(msg){ 
                    var obj = $.parseJSON(msg);
                    loadblockUI(obj.msg);
                    setTimeout('$.unblockUI();',4000);
                    if(obj.success){
                        
                        if(obj.redirect_type == 'buyerregistration'){
                            window.location = baseurl+'/profile';
                        }else if(obj.redirect_type == 'loginsuccesstocheckout'){
                            window.location = baseurl+'/profile/checkout';
                        }else if(obj.redirect_type == 'agentregistration'){
                            window.location = baseurl+'/';
                        }else if(obj.redirect_type == 'agencyregistration'){
                            window.location = baseurl+'/';
                        }
                    }
                    
                    
                }
            });
        }
    });
});


$(document).ready(function(){
	var srcHeight = $(window).height();
	var srcWidth = $(window).width();
	$(".coming-soon-page").css("height", srcHeight);
	$(".price-table-bg").css("height", srcHeight);
/* start newsletter */
var validator = $("#formnewsletter").validate({ 
//errorContainer: newsletter,
//meta: "validate",
rules: {
name: {
required: true
//,notEqual: "Name"
},

email_address: {
required: true,
email: true
}
},
submitHandler: function(form) {
var name = $("#name").val();	
var email_address = $("#email_address").val();
//  var param = "email_address="+email_address+"&name="+name+"&phone_number="+phone_number; 
var param = "email_address="+email_address;
//alert(param);
jQuery.ajax({
type: "POST",
url:baseurl+'/index/newsletter',
data: param,
success: function(msg){
//alert(msg);
//$.unblockUI();
//var msg = jQuery.parseJSON(msg);
loadblockUI(msg);
//setTimeout('$.unblockUI();',3000);
$('#formnewsletter')[0].reset();
}
});
}
});
/*END newsletter*/
});
function loadblockUI(msg){
$.blockUI({ message: '<div class="png-box"><div class="lt"></div><div class="t"></div><div class="rt"></div><div class="c"><div class="l"></div><div class="content"><div class="light-box-content">'+msg+'</div></div><div class="r"></div></div><div class="lb"></div><div class="b"></div><div class="rb"></div></div>' });
}
$(document).ready(function(){
/*
* Replace all SVG images with inline SVG
*/
jQuery('img.svg').each(function(){
var $img = jQuery(this);
var imgID = $img.attr('id');
var imgClass = $img.attr('class');
var imgURL = $img.attr('src');
jQuery.get(imgURL, function(data) {
// Get the SVG tag, ignore the rest
var $svg = jQuery(data).find('svg');
// Add replaced image's ID to the new SVG
if(typeof imgID !== 'undefined') {
$svg = $svg.attr('id', imgID);
}
// Add replaced image's classes to the new SVG
if(typeof imgClass !== 'undefined') {
$svg = $svg.attr('class', imgClass+' replaced-svg');
}
// Remove any invalid XML tags as per http://validator.w3.org
$svg = $svg.removeAttr('xmlns:a');
// Replace image with new SVG
$img.replaceWith($svg);
}, 'xml');
});
});
$(document).ready(function(){
	var srcHeight = $(window).height();
	$(".index-page #myCarousel .carousel-inner .item").css("height", srcHeight - 100);
   // $(".property-slider").css("height", srcHeight - 476);

	$('html').find('select').prepend('<div></div>');
});
$(document).ready(function() {
    var divHeight = $(".search-content").height();
    $(".btn.btn-defalut.fill").css("height", divHeight);

    $(".properties-tab .header a").click(function () {
        var tabLinks = $(this).attr('href');
        $(".properties-tab .header a").removeClass('active');
        $(this).addClass('active');
        $('.properties-tab .tabs').removeClass('active');
        $(tabLinks).addClass('active');
        return false;
    });
	
	

});

/*

$(window).on('load', function() {
	
	
var sidebarHeight = $(".side-bar").height() + 37 ; 
var pageHeigh = $(".page-heigh").height() + 37;

if(sidebarHeight > pageHeigh){	
	$('.page-heigh').css('height', sidebarHeight);
}else if(sidebarHeight < pageHeigh){
	$(".side-bar").css('height', pageHeigh);
}
//$(".page-heigh").css("height", sidebarHeight + 14)   ;

//$('.page-height').css('min-height', $('.side-bar').height()+ 71 +'px');
});
*/
$(document).ready(function(){
    $('.carousel[data-type="multi"] .item').each(function(){
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));

        for (var i=0;i<1;i++) {
            next=next.next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }

            next.children(':first-child').clone().appendTo($(this));
        }
    });
});
$(document).ready(function(){
    $('#global-luxury-slider.carousel[data-type="multi"] .item').each(function(){
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));

        for (var i=0;i<2;i++) {
            next=next.next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }

            next.children(':first-child').clone().appendTo($(this));
        }
    });


    $(".list-ico").click(function(){
        $(this).addClass("active");
        $(".grid-ico").removeClass("active");
        $(".properties-tab.view.grid").removeClass("on");
        $(".properties-tab.view.grid").addClass("off");
        $(".properties-tab.view.list").addClass("on");
        $(".properties-tab.view.list").removeClass("off");
        return false;
    });

    $(".grid-ico").click(function(){
        $(this).addClass("active");
        $(".list-ico").removeClass("active");
        $(".properties-tab.view.grid").removeClass("off");
        $(".properties-tab.view.grid").addClass("on");
        $(".properties-tab.view.list").addClass("off");
        $(".properties-tab.view.list").removeClass("on");
        return false;
    });

    $(".inner-page.property-details-page .detail-tabs .header").click(function(){
        $(this).next(".box").slideToggle();
        $(this).find("h3 i").toggleClass("on");
    });

});
$(".overview.box.open .btn").click(function(){
	//$('.overview.box.open > p').toggleClass("overlay"); 
});
$("#goToDiv").click(function() {
    $('html, body').animate({
        scrollTop: $("#mail-stop").offset().top - 100
    }, 500);
});
$(document).ready(function(){
var validator = $("#frmNewsletter").validate({
    //errorContainer: newsletter,
    //meta: "validate",
    rules: {
        name: {
            required: true
        },
        email_address: {
            required: true,
            email: true
        }
    },
    submitHandler: function(form) {
        var name = $("#name").val();
        var email_address = $("#email_address").val();
        var param = "name="+name+"&email_address="+email_address;
        jQuery.ajax({
            type: "POST",
            url:baseurl+'/index/newsletter',
            data: param,
            success: function(msg){
                $('#frmNewsletter')[0].reset();
                //$.unblockUI();
                loadblockUI(msg);
                setTimeout('$.unblockUI();',3000); 
            }
        });
    }
});
});

$(document).ready(function(){
    $('.showmore').click(function(){
        var link = $(this);
        $('.overview.box.open > p').toggleClass('overlay', function() {
            if (link.text()=="SHOW MORE") {
                link.text('SHOW LESS');
                $(".btn.showmore").addClass("minus");
                $(".btn.showmore").removeClass("plus");
            } else if (link.text()=="SHOW LESS") {
                link.text('SHOW MORE');
                $(".btn.showmore").addClass("plus");
                $(".btn.showmore").removeClass("minus");
            }
        });

    });

	
	$(".social-lounge .tablinks").click(function(){
	$(".social-lounge .tablinks").removeClass("active");
	$(this).addClass("active");
	});
	
	$(".social-lounge .tablinks").click(function(){
		//(".insta.social-grid").addClass("show-insta"); 	
		//$(".insta.social-grid").addClass("show-insta"); 
		//$(".social-lounge .tablinks").addClassClass("show-insta");
		//$(this).addClass("show-insta");
		
	});

});

$(".properties-selector").change(function() {
  var id ="#" + $(this).children(":selected").attr("class") ;
  $(".properties-tab .tabs").css("display","none");
  //alert(id);
  $(id).css("display","block");
  $('.mobile-view-on').slick('setPosition');
});
$(document).ready(function(){
$('.twitter_tab').hide();
//$('.instagram_tab').hide();
$('.facebook_tab').hide();
$('.pinterest_tab').hide();
});

function homePropertySlider(){
 $(document).ready(function(){
      if($(window).width() < 800) {
        $('.mobile-view-on').slick({

		});
      } else {
        $('.mobile-view-on').slick('unslick');
      }

  });
}

function runSlider() {
  if ($(window).width() < 800 ) {
    $('.mobile-view-on').slick({ 
      dots: false,
infinite: true,
speed: 300,
slidesToShow: 3,
slidesToScroll: 1,
responsive: [
{
breakpoint: 1024,
settings: {
dots: false,
slidesToShow: 2,
slidesToScroll: 2,
infinite: true
}
},
{
breakpoint: 600,
settings: {
dots: false,
slidesToShow: 2,
slidesToScroll: 2
}
},
{
breakpoint: 480,
settings: {
dots: false,
slidesToShow: 1,
slidesToScroll: 1
}
}
]
    });
  } else {
    //$('.slider').unslick();
  }
}
runSlider();
var r;

$(window).resize(function() {
    clearTimeout(r);
    r = setTimeout(runSlider, 500);
});
function initMasonry() {
	 $(document).ready(function(){
    var $container = $('.social-grid');
    // initialize
    $container.masonry({
     // columnWidth: '.grid-sizer',
      itemSelector: '.social-grid-item',
      isFitWidth: true,
	  isAnimated: true,
           animationOptions: {
                duration: 700,
                easing: 'linear',
                queue: false
           }
	  
    });
	
	  });
}






function threeslider(){
	
	$('.three-slider').slick({
  dots: true,
  infinite: false,
  speed: 300,
  slidesToShow: 3,
  slidesToScroll: 1,
  dots: false,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        infinite: true,
        dots: false
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

}

$(".profile-settings-page .agent-banner .btn.address-box").click(function(){
    $(".profile-detail-box.address").slideUp();
	$(".address-editor").slideDown();
});

$(".profile-settings-page .editor .btn.cancle").click(function(){
    $(".profile-detail-box.address").slideDown();
	$(".address-editor").slideUp();
});

	
$(".property-details-page .detail-tabs .header .btn.about").click(function(){
	$(this).css("display", "none");
    $(".box-open.about").slideUp();
	$(".box-open.editor.about").slideDown();
	$(".header-edit-box.about-btn").css("display", "block");
});

$(".header-edit-box.about-btn .btn.agent-about.Cancel").click(function(){
	//alert();
	$(".header-edit-box.about-btn").css("display", "none");
    $(".box-open.about").slideDown();
	$(".box-open.editor.about").slideUp();
	$(".property-details-page .detail-tabs .header .btn.about").css("display", "block");
});


$(".property-details-page .detail-tabs .header .btn.territories").click(function(){
	$(this).css("display", "none");
    $(".box-open.territories").slideUp();
	$(".box-open.editor.territories").slideDown();
	$(".header-edit-box.territories-btn").css("display", "block");
});

$(".header-edit-box.territories-btn .btn.cancle").click(function(){
	$(".header-edit-box.territories-btn").css("display", "none");
    $(".box-open.territories").slideDown();
	$(".box-open.editor.territories").slideUp();
	$(".property-details-page .detail-tabs .header .btn.territories").css("display", "block");
});

$(".cat-select .row-box.cat-header").click(function(){
	$(this).next(".cat-dropdown").slideToggle();

    var checkbox = $(this).find('input[type=checkbox]');
   checkbox.prop("checked", !checkbox.prop("checked"));
});

$("#select_all").change(function(){  //"select all" change 
    var status = this.checked; // "select all" checked status
    $('.checkbox').each(function(){ //iterate all listed checkbox items
        this.checked = status; //change ".checkbox" checked status
    });
});


$(".navbar-right .log-in").click(function(){
	$(".signin-dropdown").slideToggle(); 
	$(this).toggleClass("open")
});


(function($) {

  'use strict';

  $(document).on('show.bs.tab', '.nav-tabs-responsive [data-toggle="tab"]', function(e) {
    var $target = $(e.target);
    var $tabs = $target.closest('.nav-tabs-responsive');
    var $current = $target.closest('li');
    var $parent = $current.closest('li.dropdown');
		$current = $parent.length > 0 ? $parent : $current;
    var $next = $current.next();
    var $prev = $current.prev();
    var updateDropdownMenu = function($el, position){
      $el
      	.find('.dropdown-menu')
        .removeClass('pull-xs-left pull-xs-center pull-xs-right')
      	.addClass( 'pull-xs-' + position );
    };

    $tabs.find('>li').removeClass('next prev');
    $prev.addClass('prev');
    $next.addClass('next');
    
    updateDropdownMenu( $prev, 'left' );
    updateDropdownMenu( $current, 'center' );
    updateDropdownMenu( $next, 'right' );
  });

})(jQuery);


$("#select_all-one").change(function(){ 
    var status = this.checked; 
    $('.checkbox.one').each(function(){
        this.checked = status; 
    });
});
$('.checkbox.one').change(function(){ 
    if(this.checked == false){ 
        $("#select_all-one")[0].checked = false;
    }
    if ($('.checkbox.one:checked').length == $('.checkbox.one').length ){ 
        $("#select_all-one")[0].checked = true;
    }
});

$("#select_all-two").change(function(){ 
    var status = this.checked; 
    $('.checkbox.two').each(function(){
        this.checked = status; 
    });
});
$('.checkbox.two').change(function(){ 
    if(this.checked == false){ 
        $("#select_all-two")[0].checked = false;
    }
    if ($('.checkbox.two:checked').length == $('.checkbox.two').length ){ 
        $("#select_all-two")[0].checked = true;
    }
});

$("#select_all-three").change(function(){ 
    var status = this.checked; 
    $('.checkbox.three').each(function(){
        this.checked = status; 
    });
});
$('.checkbox.three').change(function(){ 
    if(this.checked == false){ 
        $("#select_all-three")[0].checked = false;
    }
    if ($('.checkbox.three:checked').length == $('.checkbox.three').length ){ 
        $("#select_all-three")[0].checked = true;
    }
});

$("#select_all-four").change(function(){ 
    var status = this.checked; 
    $('.checkbox.four').each(function(){
        this.checked = status; 
    });
});

$('.checkbox.four').change(function(){ 
    if(this.checked == false){ 
        $("#select_all-four")[0].checked = false;
    }
    if ($('.checkbox.four:checked').length == $('.checkbox.four').length ){ 
        $("#select_all-four")[0].checked = true;
    }
});

$("#select_all-five").change(function(){ 
    var status = this.checked; 
    $('.checkbox.five').each(function(){
        this.checked = status; 
    });
});

$('.checkbox.five').change(function(){ 
    if(this.checked == false){ 
        $("#select_all-five")[0].checked = false;
    }
    if ($('.checkbox.five:checked').length == $('.checkbox.five').length ){  
        $("#select_all-five")[0].checked = true;
    }
});

$(".cat-select .row-box.features-header h4").click(function(){
	$(this).parent().next(".cat-dropdown").slideToggle();

    var checkbox = $(this).find('input[type=checkbox]');
    checkbox.prop("checked", !checkbox.prop("checked"));
	
});






/*
$.FileDialog({
accept: "*",
cancelButton: "Close",
dragMessage: "Drop files here",
dropheight: 400,
errorMessage: "An error occured while loading file",
multiple: true,
okButton: "OK",
readAs: "DataURL",
removeMessage: "Remove&nbsp;file",
title: "Load file(s)"
});

// handle files choice when done
.on('files.bs.filedialog', function(ev) {
  var files_list = ev.files;
});

// handle dialog cancelling
on('cancel.bs.filedialog', function(ev) {
});

*/





