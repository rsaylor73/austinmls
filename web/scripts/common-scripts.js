
;(function($){
	$(function(){

		// Begin input common focus and blur for value.
		$('input:text,input:password,textarea').focus(function(){if(this.value==this.defaultValue){this.value=''}})
		$('input:text,input:password,textarea').blur(function(){if(!this.value){this.value=this.defaultValue;}})
		// Ends input common focus and blur for value.
		
        $('#tab-item li').eq(0).addClass('active')
        $('#tab-content-wrap div.tab-inner').hide()
        $('#tab-content-wrap div.tab-inner').eq(0).show()
        $('#tab-item li').each(function(i){

            $(this).click(function(){
                if( $(this).hasClass('active') ) return false

                else{
                    $('#tab-item li').removeClass('active')
                    $(this).addClass('active')
                    $('#tab-content-wrap div.tab-inner').hide()
                    $('#tab-content-wrap div.tab-inner').eq(i).show()

                }

            })

        })
        
        
        $('#login, .login-here').click(function(e){
            $('#login-section').slideDown(); 
            $('#register-section').slideUp();
            e.stopPropagation()
        })
        
        $("body").click(function(){
            $('.sign-up-section').slideUp(); 
        })
        
        $(".sign-up-section").click(function(e){
            e.stopPropagation()
        })
        
        $('#register, .register-here').click(function(e){
            e.stopPropagation()
            $('#register-section').slideDown();
            $('#login-section').slideUp(); 
           
        })

        if($("select.styled-select").length){
            $('select.styled-select').selectric();
        }

        if($("#featured-slider-main").length){
            $('.carousel-slider').slick({
                centerMode: true,
                centerPadding: '0',
                slidesToShow: 3,
                responsive: [
                    {
                        breakpoint: 991,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '0',
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 766,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '0',
                            slidesToShow: 1
                        }
                    }
                ]
            });
        }
        
        if($("#featured-carousel").length){
            $('.carousel-inner').slick({
                centerMode: true,
                centerPadding: '30px',
                slidesToShow: 5,
                responsive: [
                    {
                        breakpoint: 991,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '0',
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 766,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '0',
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 445,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '0',
                            slidesToShow: 1
                        }
                    }
                ]
            });
        }
        if($("#popup-carousel").length){
            $('#popup-carousel div.popup-carousel-inner').slick({
                centerMode: true,
                centerPadding: '30px',
                slidesToShow: 3,
                responsive: [
                    {
                        breakpoint: 991,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '0',
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 766,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '0',
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 445,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '0',
                            slidesToShow: 1
                        }
                    }
                ]
            });
        }
        
        
        if($(window).width() < 768 ){
            $(".feature-row > ul > li > a").bind('click','tuchend', function(e){
                e.preventDefault()
                
                $(".feature-row > ul > li").find("ul:visible").slideUp()
                
                if($(this).parent().find("> ul:visible").length){
                    $(this).parent().find("ul").slideUp()
                }
                
                else{
                    $(this).parent().find("ul").slideDown()
                }
            })
        }
        
        $('div.mobi-nav').click(function(){
            $('div.search-wrap').slideToggle();
        })
        
        if($(".price-info").length){
            $(function() {
                var sliderElement = $( "#pricingRange" )
                sliderElement.slider({
                    range: true,
                    min: 0,
                    step: 100,
                    max: 1200000,
                    values: [ 0, 500000],
                    slide: function( event, ui ) {
                        $( "#minRange" ).text( "$" + ui.values[ 0 ].toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
                        $( "#maxRange" ).text( "$" + ui.values[ 1 ].toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
                    }
                });
                
                $( "#minRange" ).text("$" + sliderElement.slider('values',  0).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
                $( "#maxRange" ).text("$" + sliderElement.slider('values', 1).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );

                $("#minRange").change(function() {
                    $("#slider-range").slider('values',0,$(this).val());
                });

                $("#maxRange").change(function() {
                    $("#slider-range").slider('values',1,$(this).val());
                });
                
            });
        }

        
        if($(".datepicker").length){
            $( function(){
                $(".datepicker").datepicker()
            } );
        }
        
       
        
        $("a.preview").bind('click touchend', function(e){
            e.preventDefault()
            $("body").addClass('showPopup')
            $('body, html').stop(true, true).animate({
                scrollTop: $('body').offset().top - 0
            },10)
            
            e.stopPropagation()

            $('body').bind('click touchend',function(){
                $("body").removeClass('showPopup')
                $(".property-inner").click(function(e){
                    e.stopPropagation()
                })
            })
            
        })

           
        if($('.property-details-content').length){
            $('body').addClass('property-details-page-body')
        }
        
        
        $('.selectric-info > span').each(function() {
            $(this).click(function() {
                $(this).parent().find(".multiple-select").val(null).trigger("change"); 
            });
        });
        
             
        $(".feature-row > ul > li:first-child").bind('click', function(e){
            e.stopPropagation()
        })
        
        if($(window).width() > 1024){
            $(".feature-row > ul > li:first-child").mouseenter(function(){
                $(this).find("ul").css({
                    'visibility' : 'visible',
                    'opacity' : 1,
                    'height' : 'auto'
                })
            })

            $(".feature-row > ul > li:first-child").mouseleave(function(){
                if($('#ui-datepicker-div').is(':visible')) return false 
                else{
                    $(this).find("ul").css({
                        'visibility' : 'hidden',
                        'opacity' : 0,
                        'height' : 0
                    })
                }

            })

            $('body').bind('click', function(){
                if($('#ui-datepicker-div').is(':visible')){
                    $('.feature-row > ul > li:first-child').find("ul").css({
                        'visibility' : 'hidden',
                        'opacity' : 0,
                        'height' : 0
                    })
                }
            })
        }
        
        
    
        $('#get_value').selectric().on('change', function() {
          $('div.time, div.date').fadeToggle()
        });


        
        
	})// End ready function.
	
	$(window).load(function(){
        // Begin common slider
        if ( $('.property-slider').length == 0 ) return false

        $('.property-slider').flexslider({
            animation:"slide",
            slideshow: true,
            directionNav: true,
            controlNav:false,
            pauseOnHover: true,
            slideshowSpeed: 5000,  //Integer: Set the speed of the slideshow cycling, in milliseconds
            animationSpeed: 600, 
            useCSS: false,
            start: function(slider){
                //$('body').removeClass('loading');

            }
            ,
            animationLoop: true,
            pauseOnAction: false, // default setting

            after: function(slider) {

            }
        })

    })
	
})(jQuery)

//Quad, Cubic, Quart, Quint, Sine, Expo, Circ, Elastic, Back, Bounce
jQuery.easing["jswing"]=jQuery.easing["swing"];jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(a,b,c,d,e){return jQuery.easing[jQuery.easing.def](a,b,c,d,e)},easeInQuad:function(a,b,c,d,e){return d*(b/=e)*b+c},easeOutQuad:function(a,b,c,d,e){return-d*(b/=e)*(b-2)+c},easeInOutQuad:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b+c;return-d/2*(--b*(b-2)-1)+c},easeInCubic:function(a,b,c,d,e){return d*(b/=e)*b*b+c},easeOutCubic:function(a,b,c,d,e){return d*((b=b/e-1)*b*b+1)+c},easeInOutCubic:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b+c;return d/2*((b-=2)*b*b+2)+c},easeInQuart:function(a,b,c,d,e){return d*(b/=e)*b*b*b+c},easeOutQuart:function(a,b,c,d,e){return-d*((b=b/e-1)*b*b*b-1)+c},easeInOutQuart:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b*b+c;return-d/2*((b-=2)*b*b*b-2)+c},easeInQuint:function(a,b,c,d,e){return d*(b/=e)*b*b*b*b+c},easeOutQuint:function(a,b,c,d,e){return d*((b=b/e-1)*b*b*b*b+1)+c},easeInOutQuint:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b*b*b+c;return d/2*((b-=2)*b*b*b*b+2)+c},easeInSine:function(a,b,c,d,e){return-d*Math.cos(b/e*(Math.PI/2))+d+c},easeOutSine:function(a,b,c,d,e){return d*Math.sin(b/e*(Math.PI/2))+c},easeInOutSine:function(a,b,c,d,e){return-d/2*(Math.cos(Math.PI*b/e)-1)+c},easeInExpo:function(a,b,c,d,e){return b==0?c:d*Math.pow(2,10*(b/e-1))+c},easeOutExpo:function(a,b,c,d,e){return b==e?c+d:d*(-Math.pow(2,-10*b/e)+1)+c},easeInOutExpo:function(a,b,c,d,e){if(b==0)return c;if(b==e)return c+d;if((b/=e/2)<1)return d/2*Math.pow(2,10*(b-1))+c;return d/2*(-Math.pow(2,-10*--b)+2)+c},easeInCirc:function(a,b,c,d,e){return-d*(Math.sqrt(1-(b/=e)*b)-1)+c},easeOutCirc:function(a,b,c,d,e){return d*Math.sqrt(1-(b=b/e-1)*b)+c},easeInOutCirc:function(a,b,c,d,e){if((b/=e/2)<1)return-d/2*(Math.sqrt(1-b*b)-1)+c;return d/2*(Math.sqrt(1-(b-=2)*b)+1)+c},easeInElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e)==1)return c+d;if(!g)g=e*.3;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return-(h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g))+c},easeOutElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e)==1)return c+d;if(!g)g=e*.3;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return h*Math.pow(2,-10*b)*Math.sin((b*e-f)*2*Math.PI/g)+d+c},easeInOutElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e/2)==2)return c+d;if(!g)g=e*.3*1.5;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);if(b<1)return-.5*h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)+c;return h*Math.pow(2,-10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)*.5+d+c},easeInBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;return d*(b/=e)*b*((f+1)*b-f)+c},easeOutBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;return d*((b=b/e-1)*b*((f+1)*b+f)+1)+c},easeInOutBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;if((b/=e/2)<1)return d/2*b*b*(((f*=1.525)+1)*b-f)+c;return d/2*((b-=2)*b*(((f*=1.525)+1)*b+f)+2)+c},easeInBounce:function(a,b,c,d,e){return d-jQuery.easing.easeOutBounce(a,e-b,0,d,e)+c},easeOutBounce:function(a,b,c,d,e){if((b/=e)<1/2.75){return d*7.5625*b*b+c}else if(b<2/2.75){return d*(7.5625*(b-=1.5/2.75)*b+.75)+c}else if(b<2.5/2.75){return d*(7.5625*(b-=2.25/2.75)*b+.9375)+c}else{return d*(7.5625*(b-=2.625/2.75)*b+.984375)+c}},easeInOutBounce:function(a,b,c,d,e){if(b<e/2)return jQuery.easing.easeInBounce(a,b*2,0,d,e)*.5+c;return jQuery.easing.easeOutBounce(a,b*2-e,0,d,e)*.5+d*.5+c}})