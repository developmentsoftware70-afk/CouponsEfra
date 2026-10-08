  /*============== Main Js Start ========*/

  (function ($) {
    "use strict";
  
    /*============== Header Hide Click On Body Js ========*/
    $('.navbar-toggler.header-button').on('click', function() {
      if($('.body-overlay').hasClass('show')){
        $('.body-overlay').removeClass('show');
      }else{
        $('.body-overlay').addClass('show');
      }
    });
    $('.body-overlay').on('click', function() {
      $('.header-button').trigger('click');
    });
  
  
    /* ==========================================
    *     Start Document Ready function
    ==========================================*/
    $(document).ready(function () {
      "use strict";
  
      if ($(".odometer").length) {
        var odo = $(".odometer");
        odo.each(function () {
          $(this).appear(function () {
            var countNumber = $(this).attr("data-count");
            $(this).html(countNumber);
          });
        });
      }
      
      /*================== Show Login Toggle Js ==========*/
      $('#showlogin').on('click', function () {
        $('#checkout-login').slideToggle(700);
      });
  
      /*================== Show Coupon Toggle Js ==========*/
      $('#showcupon').on('click', function () {
        $('#coupon-checkout').slideToggle(400);
      });
  
      /*========================= Slick Slider Js Start ==============*/
      $('.testimonial-slider').slick({
        slidesToShow: 3,
        slidesToScroll: 2,
        autoplaySpeed: 2000,
        speed: 1500,
        autoplay: true,
        pauseOnHover: false,
        arrows: false,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
        responsive: [
            {
              breakpoint: 1199,
              settings: {
                arrows: false,
                slidesToShow: 3,
                dots: false,
              }
            },
            {
              breakpoint: 991,
              settings: {
                arrows: false,
                slidesToShow: 2
              }
            },
            {
              breakpoint: 424,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            },
            {
              breakpoint: 767,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            }
          ]
      });
      // for slider section
      $('.section-slider').slick({
        slidesToShow: 3,
        slidesToScroll: 2,
        autoplaySpeed: 2000,
        speed: 1500,
        autoplay: true,
        pauseOnHover: false,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
        responsive: [
            {
              breakpoint: 1199,
              settings: {
                arrows: false,
                slidesToShow: 3,
                dots: false,
              }
            },
            {
              breakpoint: 991,
              settings: {
                arrows: false,
                slidesToShow: 2
              }
            },
            {
              breakpoint: 424,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            },
            {
              breakpoint: 767,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            }
          ]
      });
      // for deal section
      $('.deal-slider').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplaySpeed: 2500,
        speed: 2000,
        autoplay: false,
        loop:true,
        pauseOnHover: true,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
        responsive: [
            {
              breakpoint: 1199,
              settings: {
                arrows: false,
                slidesToShow: 3,
                dots: false,
              }
            },
            {
              breakpoint: 991,
              settings: {
                arrows: false,
                slidesToShow: 2
              }
            },
            {
              breakpoint: 424,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            },
            {
              breakpoint: 767,
              settings: {
                arrows: false,
                slidesToShow: 2
              }
            }
          ]
      });
      $('.ads-slider').slick({
        autoplay: true,
        arrows: false,
        dots: true,
        infinite: true,
        speed: 1000,
        fade: true,
        cssEase: 'linear'
      });
      // for store
      $('.store-slider').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplaySpeed: 2000,
        speed: 1500,
        autoplay: true,
        infinite: false,
        pauseOnHover: false,
        dots: false,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
        responsive: [
            {
              breakpoint: 1199,
              settings: {
                arrows: false,
                slidesToShow: 5,
                dots: false,
              }
            },
            {
              breakpoint: 991,
              settings: {
                arrows: false,
                slidesToShow: 3
              }
            },
            {
              breakpoint: 424,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            },
            {
              breakpoint: 767,
              settings: {
                arrows: false,
                slidesToShow: 2
              }
            }
          ]
      });
      // for store
      $('.popular-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplaySpeed: 2000,
        speed: 1500,
        autoplay: true,
        infinite: true,
        pauseOnHover: false,
        dots: false,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
        responsive: [
            {
              breakpoint: 1199,
              settings: {
                arrows: false,
                slidesToShow: 4,
                dots: false,
              }
            },
            {
              breakpoint: 991,
              settings: {
                arrows: false,
                slidesToShow: 3
              }
            },
            {
              breakpoint: 424,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            },
            {
              breakpoint: 767,
              settings: {
                arrows: false,
                slidesToShow: 2
              }
            }
          ]
      });
      // slider main
      /*
      $('.full-width-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplaySpeed: 2500,
        speed: 1000,
        autoplay: true,
        infinite: true,
        fade: true, // Enable fade effect
        cssEase: 'linear', // Smooth transition
        pauseOnHover: true,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
        responsive: [
            {
                breakpoint: 1199,
                settings: { arrows: false, slidesToShow: 1 }
            },
            {
                breakpoint: 991,
                settings: { arrows: false, slidesToShow: 1 }
            },
            {
                breakpoint: 767,
                settings: { arrows: false, slidesToShow: 1 }
            }
        ]
      });
      */

      $('.full-width-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplaySpeed: 2500,
        speed: 2000,
        autoplay: true,
        loop:true,
        pauseOnHover: true,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
        responsive: [
            {
                breakpoint: 1199,
                settings: { arrows: false, slidesToShow: 1 }
            },
            {
                breakpoint: 991,
                settings: { arrows: false, slidesToShow: 1 }
            },
            {
                breakpoint: 767,
                settings: { arrows: false, slidesToShow: 1 }
            }
        ]
      });
      
      $('.main-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplaySpeed: 2500,
        speed: 2000,
        autoplay: true,
        loop:true,
        pauseOnHover: true,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
        responsive: [
            {
              breakpoint: 1199,
              settings: {
                arrows: false,
                slidesToShow: 1,
                dots: false,
              }
            },
            {
              breakpoint: 991,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            },
            {
              breakpoint: 424,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            },
            {
              breakpoint: 767,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            }
          ]
      });
      // slider coupons
      $('.coupons-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 3,
        autoplaySpeed: 2500,
        speed: 2000,
        autoplay: true,
        pauseOnHover: true,
        arrows: false,
        dots: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
        responsive: [
            {
              breakpoint: 1199,
              settings: {
                arrows: false,
                slidesToShow: 4,
                dots: true,
              }
            },
            {
              breakpoint: 991,
              settings: {
                arrows: false,
                slidesToShow: 3
              }
            },
            {
              breakpoint: 424,
              settings: {
                arrows: false,
                slidesToShow: 1
              }
            },
            {
              breakpoint: 767,
              settings: {
                arrows: false,
                slidesToShow: 2
              }
            }
          ]
      });
      /*================== Sidebar Menu Js Start =============== */
      // Sidebar Dropdown Menu Start
      $(".has-dropdown > a").on('click', function() {
        $(".sidebar-submenu").slideUp(200);
        if (
          $(this)
            .parent()
            .hasClass("active")
        ) {
          $(".has-dropdown").removeClass("active");
          $(this)
            .parent()
            .removeClass("active");
        } else {
          $(".has-dropdown").removeClass("active");
          $(this)
            .next(".sidebar-submenu")
            .slideDown(200);
          $(this)
            .parent()
            .addClass("active");
        }
      });
  
      /*==================== Sidebar Icon & Overlay js ===============*/
        $(".dashboard-body__bar-icon").on("click", function() {
          $(".sidebar-menu").addClass('show-sidebar'); 
          $(".sidebar-overlay").addClass('show'); 
        });
        $(".sidebar-menu__close, .sidebar-overlay").on("click", function() {
          $(".sidebar-menu").removeClass('show-sidebar'); 
          $(".sidebar-overlay").removeClass('show'); 
        });
    
     
    });
      /*==========================================
      *      End Document Ready function
      // ==========================================*/
      /*========================= Preloader Js Start =====================*/
         
        $(window).on("load", function(){
        $("#loading").fadeOut();
        })
  
      /*========================= Header Sticky Js Start ==============*/
      $(window).on('scroll', function() {
        if ($(window).scrollTop() >= 300) {
          $('.header').addClass('fixed-header');
        }
        else {
            $('.header').removeClass('fixed-header');
        }
      }); 
      
      /*============================ Scroll To Top Icon Js Start =========*/
      var btn = $('.scroll-top');
      $(window).scroll(function() {
        if ($(window).scrollTop() > 300) {
          btn.addClass('show');
        } else {
          btn.removeClass('show');
        }
      });
      btn.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop:0}, '300');
      });
      
      /*============================ header menu show hide =========*/
      $('.sidebar-menu-show-hide').on('click', function() {
          $('.sidebar-menu-wrapper').addClass('show');
          $(".sidebar-overlay").addClass('show'); 
      });
  
      $('.sidebar-overlay, .close-hide-show').on('click', function() {
          $('.sidebar-menu-wrapper').removeClass('show');
          $(".sidebar-overlay").removeClass('show'); 
      });
  
        /*============================ Dashboard Menu show hide =========*/
        $(".dashboard-show-hide").on("click", function () {
          $(".dashboard_profile").addClass("show");
          $(".sidebar-overlay").addClass("show");
      });
    
      $(".sidebar-overlay, .close-hide-show").on("click", function () {
          $(".dashboard_profile").removeClass("show");
          $(".sidebar-overlay").removeClass("show");
      }); 
        /*---------- 05. Scroll To Top ----------*/
      // progressAvtivation
      if($('.scroll-top').length > 0) {
        var scrollTopbtn = document.querySelector('.scroll-top');
        var progressPath = document.querySelector('.scroll-top path');
        var pathLength = progressPath.getTotalLength();
        progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
        progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
        progressPath.style.strokeDashoffset = pathLength;
        progressPath.getBoundingClientRect();
        progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';      
        var updateProgress = function () {
            var scroll = $(window).scrollTop();
            var height = $(document).height() - $(window).height();
            var progress = pathLength - (scroll * pathLength / height);
            progressPath.style.strokeDashoffset = progress;
        }
        updateProgress();
        $(window).scroll(updateProgress);   
        var offset = 50;
        var duration = 800;
        jQuery(window).on('scroll', function() {
            if (jQuery(this).scrollTop() > offset) {
                jQuery(scrollTopbtn).addClass('show');
            } else {
                jQuery(scrollTopbtn).removeClass('show');
            }
        });             
        jQuery(scrollTopbtn).on('click', function(event) {
            event.preventDefault();
            jQuery('html, body').animate({scrollTop: 0}, duration);
            return false;
        })
  }
  
      $(".toggle-password-change").on('click', function() {
        var targetId = $(this).data("target");
        var target = $("#" + targetId);
        var icon = $(this);
        if (target.attr("type") === "password") {
            target.attr("type", "text");
            icon.removeClass("fa-eye-slash");
            icon.addClass("fa-eye");
        } else {
            target.attr("type", "password");
            icon.removeClass("fa-eye");
            icon.addClass("fa-eye-slash");
        }
      });
      // wow js
      if (window.location.pathname === '/' || window.location.pathname === '/index') {
          new WOW().init();
      }  
  
      // For Categories
      $(function () {
        "use strict";
  
        var itemsToShow = 6;
        // Show the initial set of items
        $(".check-item").slice(0, itemsToShow).show();
  
          $(".show-more-button").on('click', function (e) {
              e.preventDefault();
              var hiddenItems = $(".check-item:hidden");
  
              if (hiddenItems.length >= 0) {
                  // Show the next set of items
                  hiddenItems.slice(0, 9999).slideDown();
                  $(this).hide();
              } 
          });
      });
  
      // For Stores
      $(function () {
        "use strict";
  
        var itemsToShow = 6;
        // Show the initial set of items
        $(".check-item-stores").slice(0, itemsToShow).show();
  
          $(".show-more-button-stores").on('click', function (e) {
              e.preventDefault();
              var hiddenItems = $(".check-item-stores:hidden");
  
              if (hiddenItems.length >= 0) {
                  // Show the next set of items
                  hiddenItems.slice(0, 9999).slideDown();
                  $(this).hide();
              } 
          });
      });
  
  
  
  })(jQuery);
  