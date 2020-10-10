(function ($) {
    "use strict";

    /*----------------------------
      Header Top JS
    ------------------------------ */
    $(".slide-toggle").on('click', function () {
        $(".show-toggle").slideToggle();
        $(".show-toggle-2").css("display", "none");
        $(".show-toggle-3").css("display", "none");
    });
    $(".slide-toggle-2").on('click', function () {
        $(".show-toggle-2").slideToggle();
        $(".show-toggle").css("display", "none");
        $(".show-toggle-3").css("display", "none");
    });
    $(".slide-toggle-3").on('click', function () {
        $(".show-toggle-3").slideToggle();
        $(".show-toggle").css("display", "none");
        $(".show-toggle-2").css("display", "none");
    });

    /*-------------------------
      showcoupon toggle function
    --------------------------*/
    $('#showcoupon').on('click', function () {
        $('#checkout_coupon').slideToggle(900);
    });

    /*-------------------------
      Create an account toggle function
    --------------------------*/
    $('#cbox').on('click', function () {
        $('#cbox_info').slideToggle(900);
    });

    /*-------------------------
      Create an account toggle function
    --------------------------*/
    $('#ship-box').on('click', function () {
        $('#ship-box-info').slideToggle(1000);
    });


    /*----------------------------
     jQuery mainmenu
    ------------------------------ */
    $(".product-menu-title").on("click", function () {
        $(".product_vmegamenu").slideToggle(500);
    });

    /*----------------------------
     jQuery MeanMenu
    ------------------------------ */
    jQuery('#mobile-menu').meanmenu();


    /*----------------------------
      Nivo-Slider
    ------------------------------ */
    //$('#mainSlider').nivoSlider({
    //    effect: 'random', // Specify sets like: 'fold,fade,sliceDown'
    //    slices: 15, // For slice animations
    //    boxCols: 8, // For box animations
    //    boxRows: 4, // For box animations
    //    animSpeed: 1000, // Slide transition speed
    //    pauseTime: 3, // How long each slide will show
    //    startSlide: 0, // Set starting Slide (0 index)
    //    directionNav: true, // Next & Prev navigation
    //    controlNav: false, // 1,2,3... navigation
    //    controlNavThumbs: false, // Use thumbnails for Control Nav
    //    pauseOnHover: true, // Stop animation while hovering
    //    manualAdvance: true, // Force manual transitions
    //    prevText: '<i class="fa fa-angle-left" aria-hidden="true"></i>', // Prev directionNav text
    //    nextText: '<i class="fa fa-angle-right" aria-hidden="true"></i>', // Next directionNav text
    //    randomStart: true, // Start on a random slide
    //    beforeChange: function () {}, // Triggers before a slide transition
    //    afterChange: function () {}, // Triggers after a slide transition
    //    slideshowEnd: function () {}, // Triggers after all slides have been shown
    //    lastSlide: function () {}, // Triggers when last slide is shown
    //    afterLoad: function () {} // Triggers when slider has loaded
    //});
    $('#mainSlider').owlCarousel({
        nav: true,
        autoplay: 3000,
        items: 1,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        autoHeight: true,
        loop: true
    });

    /*----------------------------
     slider-product-active
    ------------------------------ */
    $('.slider-product-active').owlCarousel({
        autoplay: 3000,
        nav: true,
        loop: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            767: {
                items: 2
            },
            1000: {
                items: 2
            }
        }
    });

    /*----------------------------
     slider-product-active-2
    ------------------------------ */
    $('.slider-product-active-2').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            450: {
                items: 2
            },
            767: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });

    /*----------------------------
     slider-product-active-3
    ------------------------------ */
    $('.slider-product-active-3').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            450: {
                items: 2
            },
            767: {
                items: 2
            },
            1000: {
                items: 6
            }
        }
    });

    /*----------------------------
     feature-product-active-3
    ------------------------------ */
    $('.feature-product-active-3').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            450: {
                items: 2
            },
            767: {
                items: 3
            },
            1000: {
                items: 5
            }
        }
    });

    /*----------------------------
     feature-product at discount today banner
     ------------------------------ */
    $('.feature-tab-product-discount').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            450: {
                items: 2
            },
            767: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });

    /*----------------------------
     feature-product banyak at best sellers product
     ------------------------------ */
    $('.feature-product-active-banyak').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: false,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            450: {
                items: 2
            },
            767: {
                items: 6
            },
            1000: {
                items: 8
            }
        }
    });

    /*----------------------------
     tab-product-active
    ------------------------------ */
    $('.tab-product-active').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: false,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            767: {
                items: 2
            },
            1200: {
                items: 2
            }
        }
    });
    /*----------------------------
     single-product-items-active
    ------------------------------ */
    $('.single-product-items-active').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            767: {
                items: 2
            },
            1000: {
                items: 1
            }
        }
    });
    /*----------------------------
     single-product-items-2-active
    ------------------------------ */
    $('.single-product-items-2-active').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            767: {
                items: 2
            },
            1000: {
                items: 2
            }
        }
    });
    /*----------------------------
     special-products-active
    ------------------------------ */
    $('.special-products-active').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            767: {
                items: 2
            },
            1000: {
                items: 1
            }
        }
    });
    /*----------------------------
     clinet-active
    ------------------------------ */
    $('.clinet-active').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });
    /*----------------------------
     feature-product-active
    ------------------------------ */
    $('.feature-product-active').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            767: {
                items: 2
            },
            991: {
                items: 3
            },
            1200: {
                items: 4
            }
        }
    });
    /*----------------------------
     new-product-active
    ------------------------------ */
    $('.new-product-active').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            767: {
                items: 2
            },
            991: {
                items: 3
            },
            1200: {
                items: 4
            }
        }
    });

    /*----------------------------
     brand-active
    ------------------------------ */
    $('.brand-active').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            450: {
                items: 2
            },
            767: {
                items: 4
            },
            1000: {
                items: 6
            }
        }
    });

    /*----------------------------
     brand-active-3
     ------------------------------ */
    $('.brand-active-3').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            450: {
                items: 4
            },
            767: {
                items: 6
            },
            1000: {
                items: 8
            }
        }
    });
    /*----------------------------
    brand-active-2
    ------------------------------ */
    $('.brand-active-2').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            450: {
                items: 2
            },
            767: {
                items: 3
            },
            1000: {
                items: 3
            }
        }
    });
    /*----------------------------
    blog-active
    ------------------------------ */
    $('.blog-active').owlCarousel({
        autoplay: 3000,
        loop: true,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            767: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });
    /*----------------------------
     tooltip-active
    ------------------------------ */
    $('[data-toggle="tooltip"]').tooltip();

    /*---------------------------------
        Scroll Up
    -----------------------------------*/
    $.scrollUp({
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade',
        scrollText: '<i class="fa fa-angle-up"></i>'
    });


    /*----------------------------
     slider-range
    ------------------------------ */
    $(function () {

        var mindata = $('#min-price').val() == '' ? 0 : parseInt($('#min-price').val());
        var maxdata = $('#max-price').val() == '' ? 2000000 : parseInt($('#max-price').val());

        $("#slider-range").slider({
            range: true,
            min: mindata,
            max: maxdata,
            step: 1,
            values: [mindata, maxdata],
            slide: function (event, ui) {
                $("#amount").val(ui.values[0] + "-" + ui.values[1]);
                $("#show-slider").val("Rp. " + ui.values[0].toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + " - Rp. " + ui.values[1].toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));

                $('#min-price').val(ui.values[0]);
                $('#max-price').val(ui.values[1]);
            },
            /*change: function(event, ui) {
                
                var currentUrl = "<?php echo base_url().'Product'?>";
                var keyword = $('#keyword').val();
                var sort = $('#sort').val();
                var MerchantId = $('#MerchantId').val();
                var rating = $('#rating').val();
                var priceRange = $('#amount').val();
                var strRplc = priceRange;
                var url = currentUrl + '?keyword='+keyword+'&merchant='+MerchantId+'&rating='+rating+'&sort='+sort+'&priceRange='+strRplc;
                window.location.href = url;

            }*/
        });

        $("input.sliderValue").change(function () {
            // alert($('#max-price').val());
            var mindata = $('#min-price').val() == '' ? 0 : parseInt($('#min-price').val());
            var maxdata = $('#max-price').val() == '' ? 0 : parseInt($('#max-price').val());

            $("#slider-range").slider({
                range: true,
                min: mindata,
                max: maxdata,
                step: 1,
                values: [mindata, maxdata],
                slide: function (event, ui) {
                    $("#amount").val(ui.values[0] + "-" + ui.values[1]);
                    $("#show-slider").val("Rp. " + ui.values[0].toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + " - Rp. " + ui.values[1].toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
                    $('#min-price').val(ui.values[0]);
                    $('#max-price').val(ui.values[1]);
                }
            });
            $("#amount").val("" + mindata + "-" + maxdata);

            $("#show-slider").val("Rp. " + mindata.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") +
                " - Rp. " + maxdata.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
        });

        $("#amount").val("" + mindata + "-" + maxdata);

        $("#show-slider").val("Rp. " + mindata.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") +
            " - Rp. " + maxdata.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));

    });

    /*----------------------------
  details-tab
------------------------------ */
    $('.details-tab').owlCarousel({
        nav: true,
        margin: 10,
        navText: ['<i class="fa fa-long-arrow-left"></i>', '<i class="fa fa-long-arrow-right"></i>'],
        responsive: {
            0: {
                items: 2
            },
            767: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    })
    /*----------------------------
      MagnificPopup
    ------------------------------ */

    $('.popup-link').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });

    $('.konten-modal-kisel').magnificPopup({
        type: 'inline'
    });

    /*----------------------------
      Zoomple
    ------------------------------ */
    //$('.zoomple').zoomple({
    //    blankURL: 'images/blank.gif',
    //    bgColor: '#90D5D9',
    //    loaderURL: 'images/loader.gif',
    //    offset: {
    //        x: -150,
    //        y: -150
    //    },
    //    roundedCorners: true
    //});

})(jQuery);