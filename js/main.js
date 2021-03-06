(function($) {

  // breakpoint vars
  var large_break = 980,
      medium_break = 840,
      small_break = 580;

  // debounce fn from david walsh
  // http://davidwalsh.name/javascript-debounce-function
  function debounce(func, wait, immediate) {
  	var timeout;
  	return function() {
  		var context = this, args = arguments;
  		var later = function() {
  			timeout = null;
  			if (!immediate) func.apply(context, args);
  		};
  		var callNow = immediate && !timeout;
  		clearTimeout(timeout);
  		timeout = setTimeout(later, wait);
  		if (callNow) func.apply(context, args);
  	};
  }


  // menu
  if(Modernizr.touch) {
    // dropdowns open on tap
    $('.main-nav').on('focus, click', '.has-dropdown > a', function(e) {
      e.preventDefault();

      $(this).parent('li').toggleClass('active');
    }).on('mouseout', function() {
      $(this).parent('li').removeClass('active');
    });
  } else {
    // sticky menu
    // var lastScrollTop   = 0,
    //     banner          = $('[role="banner"]'),
    //     mainNav         = banner.find('.main-nav');
    //
    // var updateSticky = debounce(function() {
    //   var st = $(this).scrollTop();
    //   if ( st > lastScrollTop ) {
    //     // if scrolling down, no sticky
    //     if (mainNav.hasClass('sticky')) {
    //       mainNav.slideUp(150, function() {
    //         mainNav.removeClass('sticky').show();
    //       });
    //     }
    //   } else {
    //     if ( st > (banner.offset().top + banner.outerHeight()) ) {
    //       // if it doesn't already have the sticky class, and it's moved enough down
    //       if ( !mainNav.hasClass('sticky') && lastScrollTop - st > 10 ) {
    //         mainNav.hide().addClass('sticky').slideDown();
    //       }
    //     } else {
    //       // remove sticky class if back at the top of the page
    //       mainNav.removeClass('sticky');
    //     }
    //   }
    //   lastScrollTop = st;
    // }, 10);
    // window.addEventListener('scroll', updateSticky);
  }

  $('.mobile-menu').on('click', '.has-dropdown > a', function(e) {
    e.preventDefault();

    var $this = $(this);

    if ($this.parent('li').hasClass('active')) {
      $this.parent('li').removeClass('active');
      close_mobile_accordion($this.siblings('ul'));
    } else {
      close_mobile_accordion($mobile_menu.find('.has-dropdown.active ul'));
      $mobile_menu.find('.has-dropdown').removeClass('active');
      $this.parent('li').addClass('active');
      open_mobile_accordion($this.siblings('ul'));
    }
  });

  // move menu to mobile menu for tablet & down
  if ( Modernizr.mq('only screen and (max-width:' + medium_break + 'px)') ) {
    console.log("medium screen or down");

    var $main_menu = $('.main-menu');

    if ($main_menu.hasClass('blog-main-menu')) {
      $main_menu.find('li:first-child').hide();
      $main_menu.prepend('<li class="primary"><a href="/blog">The Blog</a></li>');
    }

    // add main nav to mobile menu
    var $mobile_menu = $('.mobile-menu'),
        $main_nav = $main_menu.children('li');

    $mobile_menu.append($main_nav);

    $('.mobile-menu-trigger').on('click', function(e) {
      e.preventDefault();
      if ( $(this).hasClass('active') ) {
        $(this).removeClass('active');
        close_mobile_menu();
      } else {
        $(this).addClass('active');
        open_mobile_menu();
      }
    });
  }

  // mobile menu fn's
  function open_mobile_menu() {
    var $container = $('.container'),
        $menu_items = $('.mobile-menu').children('li');

    $('body').addClass('menu-open');

    // set up li's to be off screen
    TweenLite.set($menu_items, {
      x: 250,
      opacity: 0.5
    });

    // animate container to the left
    TweenLite.to($container, 0.3, {
      x: -250,
      ease: Power3.easeOut
    });

    // stagger animation of menu items
    for (var i = 0; i < $menu_items.length; i++) {
      TweenLite.to($menu_items[i], 0.2, {
        x: 0,
        opacity: 1,
        delay: 0.075*i + 0.05
      });
    }
  }

  function close_mobile_menu() {
    TweenLite.to($('.container'), 0.3, {
      x: 0,
      ease: Power3.easeOut,
      onComplete: function() {
        $('body').removeClass('menu-open');
      }
    });
  }

  function open_mobile_accordion(ul) {
    var $menu_items = ul.children('li'),
        height = $menu_items.length * 38 + 8; //$menu_items.length * 31 + 8

    TweenLite.set($menu_items, {
      x: 150,
      opacity: 0.5
    });

    TweenLite.to(ul, 0.25, {
      height: height,
      ease: Power3.easeOut
    });

    for (var i = 0; i < $menu_items.length; i++) {
      TweenLite.to($menu_items[i], 0.2, {
        x: 0,
        opacity: 1,
        delay: 0.06*i + 0.025
      });
    }
  }

  function close_mobile_accordion(ul) {
    TweenLite.to(ul, 0.25, {
      height: 0,
      ease: Power3.easeOut
    });
  }

  // forms
  $('.input-wrapper').on('focus', 'input, textarea', function() {
    $(this).parent('.input-wrapper').addClass('focused');
  }).on('blur', 'input, textarea', function() {
    $(this).parent('.input-wrapper').removeClass('focused');
  });

  $('.select-box').on('change', 'select', function(e) {
    if (this.value) {
      $(this).parent('.select-box').addClass('selected');
    } else {
      $(this).parent('.select-box').removeClass('selected');
    }
  });

  $('.date-input input').datepicker({
    autoclose: true,
    format: "mm/dd/yyyy"
  }).on('changeDate', function() {
    $(this).parent().addClass('selected');
  });

  $('.input-switch').on('change', function() {
    var switch_id = $(this).data('switchto'),
        $targets = $(this).parents('.row').find('.switch-target');

    $targets.removeClass('active').filter('#' + switch_id).addClass('active');
  });

  // file upload
  var brief_uploader = new plupload.Uploader({
    runtimes : 'html5,flash,silverlight,html4',

    browse_button : $('.add-files')[0],
    container: $('.uploaded-files')[0],
    drop_element: $('.upload-box')[0],

    url : "/request-a-quote/upload/",

    filters : {
        max_file_size : '100mb',
        mime_types: [
          {title : "Doc files", extensions : "pdf,doc,docx"}
        ]
    },

    // Flash settings
    flash_swf_url : '/bower_components/plupload/js/Moxie.swf',

    // Silverlight settings
    silverlight_xap_url : '/bower_components/plupload/js/Moxie.xap',


    init: {
        PostInit: function() {
          //document.getElementById('filelist').innerHTML = '';

          // drag/drop highlighting
          if(brief_uploader.runtime === 'html5') {
            $('.upload-box, .upload-box div').on('dragenter', function() {
                $('.upload-box').addClass('incoming');
            });

            $('.upload-box, .upload-box div').on('dragleave drop', function() {
                $('.upload-box').removeClass('incoming');
            });
          }

          // remove files logic
          $('.uploaded-files').on('click', '.remove-upload', function(e) {
            e.preventDefault();

            var $file_el = $(this).parent(),
                file_id = $file_el.attr('id'),
                file = brief_uploader.getFile(file_id),
                file_status = file.status,
                $size_indicator = $('.upload-box').find('.file-size span'),
                cur_size = parseFloat($size_indicator.html());

            if (brief_uploader.state == plupload.STARTED && file_status == plupload.UPLOADING) {
              console.log("canceling upload");
              brief_uploader.stop();
              brief_uploader.removeFile(file);
              brief_uploader.start();
            } else {
              console.log("removing file");
              cur_size -= (file.size/1000000);
            }

            $file_el.remove();
            $size_indicator.html(cur_size.toFixed(2));
          });
        },

        FilesAdded: function(up, files) {
          var $size_indicator = $('.upload-box').find('.file-size span'),
              cur_size = parseFloat($size_indicator.html());

          if ($('.upload-box').hasClass('initial')) {
            $('.upload-box').removeClass('initial');
            $('.add-files').html('select more files');
          }
          plupload.each(files, function(file, i) {
            $('.uploaded-files').append('<div id="' + file.id + '"><span class="name">' + file.name + '</span><span class="bg"></span><a href="#" class="remove-upload">cancel</a><input type="hidden" name="attachments_realname[]" value=""><input type="hidden" name="attachments_name[]" value="' + file.name + '"></div>');
            cur_size += (file.size/1000000);
          });

          brief_uploader.start();
          $size_indicator.html(cur_size.toFixed(2));
        },

        FileUploaded: function(up, file, request) {
          // Get request JSON
          var response = $.parseJSON(request.response);

          // Update hidden input with new filename
          $('#'+file.id).find('input:first').val(response.result.server_filename);
        },

        FilesRemoved: function(up, files) {
          console.log(files);
        },

        UploadProgress: function(up, file) {
          $('#' + file.id).children('.bg').css('width', file.percent + '%');
          if (file.percent > 99) {
            $('#' + file.id).addClass('complete');
          }
        },

        Error: function(up, err) {
          console.log("error:", err.code, err.message);
        }
    }
  });

  brief_uploader.init();

  // monkee quote form
  if ($('.request-quote-form').length > 0) {

    // attach validation
    $('.request-quote-form').validationEngine('attach', {
      showArrow: false,
      binded: false,
      promptPosition: "bottomLeft",
      'custom_error_messages' : {
        'required': { 'message': 'Oops, looks like you forgot to add this field' }
      }
    });

    // on blur, validate and set opacity
    $('.request-quote-form').on('blur', '.form-part1 input', function() {
      var $this = $(this),
          $steps = $('.request-quote-form').find('.monkee-step'),
          $monkee = $('.request-quote-form').find('.monkee'),
          is_valid = !$this.validationEngine('validate');

      // test if parent should be marked complete
      if (is_valid) {
        if ( $this.parent().is(':last-child') ) $this.parents('.form-step').addClass('complete');
      } else {
        $this.parents('.form-step').removeClass('complete');
      }

      // change opacity of monkee
      var complete_steps = $steps.filter('.complete').length;
      if (complete_steps > 0) {
        var opacity = 1 - complete_steps/$steps.length;
        $monkee.children('.dark-monkee').css('opacity', opacity);
        console.log("opacity should be", opacity);
      }
      // speech bubble
      if ( complete_steps === $steps.length ) {
        $monkee.addClass('speak');
      } else {
        $monkee.removeClass('speak');
      }
    });
  }

  var PortfolioSlider = function(el) {
    this.$el = $(el);
    this.$screenContainer = this.$el.find('.screens');
    this.$screens = this.$screenContainer.children('.screen');
    this.$thumbs = this.$el.find('.thumbs');
    this.ratios = {
      desktop: 0.727,
      tablet: 1.328,
      phone: 2.08
    };
    this.margin = 160;
    this.width = this.$screenContainer.width();
    this.thumbRatio = 0.2;
    this.activeSlide = 'desktop';
    this.siteName = this.$el.data('site');
  };

  PortfolioSlider.prototype._getX = function(pos, slide, ratio) {
    // get the x-translate value
    var abs_value = ( this.width - ($(slide).width() * ratio) ) / 2;
    if ( pos == 'left' ) {
      return -abs_value;
    } else {
      return abs_value;
    }
  };

  PortfolioSlider.prototype._moveSlide = function(el, pos, ratio) {
    var thumbRatio = ratio ? ratio : this.thumbRatio,
        scale = pos == 'active' ? 1 : thumbRatio,
        x = pos == 'active' ? 0 : this._getX(pos, el, thumbRatio);

    console.log("moving slide", el, "to position", pos);

    TweenLite.to(el, 0.5, {
      scale: scale,
      x: x
    });
  };

  PortfolioSlider.prototype.transitionSlides = function(direction) {
    // if it's the first transition, remove the class "initial"
    if ( this.$screenContainer.hasClass('initial') )
      this.$screenContainer.removeClass('initial');

    for (var key in this.$screens) {
      var el = this.$screens[key],
          curPos = el.hasClass('active')? 'active' : el.hasClass('right')? 'right' : 'left',
          nextPos;

      if ( (curPos == 'active' && direction == 'right') || (curPos == 'right' && direction == 'left') )
        nextPos = 'left';
      else if ( (curPos == 'left' && direction == 'right') || (curPos == 'active' && direction == 'left') )
        nextPos = 'right';
      else
        nextPos = 'active';

      // do z-index stuff
      if ( curPos == 'active' || nextPos == 'active' ) {
        el.css('z-index', 2);
      } else {
        el.css('z-index', 1);
      }

      this._moveSlide(el, nextPos);

      // add correct class
      el.removeClass(curPos).addClass(nextPos);
    }
  };

  PortfolioSlider.prototype.updateScreen = function(screens) {
    for (var key in this.$screens) {
      var el = this.$screens[key].children('.screen-inner'),
          fileName = screens[key],
          image = document.createElement('img');

      image.src = fileName;
      el.html(image);
    }
  };

  PortfolioSlider.prototype.initFullSlider = function() {
    var containerWidth = this.width - this.margin*2,
        containerHeight = this.$screenContainer.height(),
        self = this,
        numScreens = 0;

    // we'll want to save screens by device type
    var screens = {};

    // set height, width, and position for all screens
    this.$screens.each(function() {
      var $this = $(this),
          type = $this.data('device');

      // horizontal tablet or phone
      if ($this.hasClass('horizontal')) {
        // inverse ratio
        self.ratios[type] = 1/self.ratios[type];
      }

      var height = self.ratios[type] > 1? containerHeight : containerWidth*self.ratios[type],
          width = self.ratios[type] < 1? containerWidth : containerHeight/self.ratios[type];

      $this.css({
        height: height + 'px',
        width: width + 'px',
        left: containerWidth/2 - width/2 + self.margin + 'px',
        top: containerHeight/2 - height/2 + 'px'
      });

      screens[type] = $this;
      numScreens++;

    });

    // save new screens object so we can access by device type
    this.$screens = screens;

    // do slider stuff if there are at least three screens
    if ( numScreens > 2 ) {
      // set phone and tablet to be right and left
      self._moveSlide(this.$screens.phone, 'left', 0.6);
      self._moveSlide(this.$screens.tablet, 'right', 0.6);

      // add click event to portfolio nav
      $('.slider-nav').on('click', 'a', function(e) {
        e.preventDefault();

        if ( $(this).hasClass('port-left') ) {
          self.transitionSlides('left');
        } else {
          self.transitionSlides('right');
        }

        // remove scroll callout, if still present
        if (self.$screens.desktop.find('.scroll-msg').length > 0)
          self.$screens.desktop.find('.scroll-msg').remove();
      });
    }

  };

  PortfolioSlider.prototype.initMobileSlider = function() {
    var containerWidth = this.width,
        self = this,
        numScreens = this.$screens.length;

    // we'll want to save screens by device type
    var screens = {};

    // set height, width, and position for all screens
    this.$screens.each(function() {
      var $this = $(this),
          type = $this.data('device'),
          height = null,
          width = null,
          left = 0,
          top = 0;

      // no tablet view on mobile
      if ( type == 'tablet' ) return true;

      // set mobile and desktop sizes
      if ( type == 'desktop' && numScreens > 1 ) {
        width = containerWidth * 1.2;
        height = width * self.ratios[type];
        left = containerWidth * 0.2;
      } else if ( type == 'desktop' && numScreens == 1 ) {
        width = containerWidth * 0.79;
        height = width * self.ratios[type];
        left = containerWidth * 0.105;
      } else if ( type == 'phone' ) {
        width = containerWidth * 0.4;
        height = width * self.ratios[type];
        top = height * 0.15;
        left = containerWidth * 0.05;
      }

      $this.css({
        height: height + 'px',
        width: width + 'px',
        left: left + 'px',
        top: top + 'px'
      });

      screens[type] = $this;

    });

    // save new screens object so we can access by device type
    this.$screens = screens;

    // add class for single screen
    if (numScreens == 1) {
      console.log("only one screen");
      this.$el.addClass('single-screen');
    }
  };

  PortfolioSlider.prototype.init = function() {
    var self = this;

    // init full slider if not mobile
    if ( Modernizr.mq('only screen and (min-width: ' + small_break + 'px)') ) {
      this.initFullSlider();
    } else {
      this.initMobileSlider();
    }

    this.$thumbs.find('.thumbnail:first').addClass('active');

    // add click event to portfolio thumbs
    this.$thumbs.on('click', '.thumbnail', function(e) {
      e.preventDefault();

      self.$thumbs.find('.thumbnail').removeClass('active');
      $(this).addClass('active');

      var screens = [];
      screens.desktop = $(this).data('screen-desktop');
      screens.tablet = $(this).data('screen-tablet');
      screens.phone = $(this).data('screen-phone');
      self.updateScreen(screens);
    });

    // remove scroll callout on scroll if there is a scroll callout
    var $scroll_callout = this.$screenContainer.find('.scroll-msg');
    if ($scroll_callout.length > 0) {
      $scroll_callout.parent('.screen-inner').on('scroll', function() {
        $(this).children('.scroll-msg').remove();
        $(this).off('scroll');
      });
    }

  };

  if ( $('.port-slider').length > 0 ) {
    var port_slider = new PortfolioSlider('.port-slider');
    port_slider.init();
  }

  // slick sliders
  $('.quote-slider').slick({
    dots: true,
    arrows: false,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    slide: 'li'
  });

  $('.thumbs-slider').slick({
    dots: false,
    arrows: true,
    slidesToShow: 3,
    slidesToScroll: 3,
    infinite: false,
    swipeToSlide: true
  });

  if($('.fullwidth-slider ul li').length > 1) {
    $('.fullwidth-slider ul').slick({
      dots: false,
      arrows: true,
      slidesToShow: 1,
      slide: 'li',
      centerMode: true,
      centerPadding: '140px',
      onInit: function() {
        $('.fullwidth-slider .slick-prev').detach().appendTo('.slick-active .slick-photo-wrapper');
        $('.fullwidth-slider .slick-next').detach().appendTo('.slick-active .slick-photo-wrapper');
      },
      onAfterChange: function() {
        $('.fullwidth-slider .slick-prev').detach().appendTo('.slick-active .slick-photo-wrapper');
        $('.fullwidth-slider .slick-next').detach().appendTo('.slick-active .slick-photo-wrapper');
      },
      responsive: [{
        breakpoint: small_break,
        settings: {
          dots: false,
          arrows: false,
          infinite: true,
          slidesToShow: 1,
          slide: 'li',
          centerMode: true,
          centerPadding: '80px'
        }
      }]
    });
  }

  // home page hero slideshow
  // var HomeSlideshow = function(el) {
  //   this.$el = $(el);
  //   this.$current = this.$el.find('.current');
  //   this.$next = this.$el.find('.next');
  //   this.$nav = this.$el.find('.slider-nav');
  //   this.$caption = this.$el.find('.caption');
  //   this.maxBlur = 120;
  //   this.steps = 3;
  //   this.curSlide = 1;
  //   this.isMobile = Modernizr.mq('only screen and (max-width:' + medium_break + 'px)');
  // };
  //
  // HomeSlideshow.prototype.addBlurCanvas = function(el, source, animate) {
  //   var self = this,
  //       $img = $('<img width="' + this.width + '" height="' + this.height + '">'),
  //       increment = this.maxBlur/this.steps;
  //
  //   $img.load(function() {
  //     console.log("image loads");
  //     el.append($img);
  //
  //     // no canvas for mobile
  //     if (!self.isMobile) {
  //       // skip first increment for smoother blur
  //       for (var i = 1; i < self.steps; i++) {
  //         var canvas = document.createElement('canvas');
  //
  //         canvas.width = self.width;
  //         canvas.height = self.height;
  //         canvas.className = 'canvas' + i;
  //         el.append(canvas);
  //
  //         stackBlurImage( $img[0], canvas, (increment * i + increment), false );
  //       }
  //     }
  //
  //     // if animate is true, immediately animate slide transition
  //     if (animate) {
  //       self.animate(self.updateSlides);
  //     }
  //   });
  //   $img.attr('src', source);
  // };
  //
  // HomeSlideshow.prototype.animate = function(callback, params) {
  //   var anim = new TimelineLite(),
  //       self = this;
  //
  //   if (!params) params = [];
  //
  //   // update slide menu
  //   this.$nav.children('li').removeClass('active');
  //   this.$nav.children('[data-id=' + this.curSlide + ']').addClass('active');
  //
  //   if (this.isMobile) {
  //     // mobile animation
  //     console.log("doing mobile animation");
  //
  //     anim.to(this.$current, 0.3, { opacity: 0 });
  //     anim.to(this.$caption, 0.3, {
  //       opacity: 0,
  //       onComplete: function() {
  //         var curHeight = self.$caption.outerHeight(),
  //             innerHeight = self.$caption.height();
  //
  //         self.$caption.height(innerHeight + 'px');
  //         self.updateCaption(self.curSlide);
  //
  //         // get new height
  //         var newHeight = self.$caption.children('.caption-title').height() + self.$caption.children('.caption-content').height() + (curHeight - innerHeight);
  //
  //         anim.to(self.$caption, 0.3, {
  //           opacity: 1,
  //           height: newHeight
  //         });
  //       }
  //     }, '-=0.3');
  //     anim.to(this.$next, 0.3, { opacity: 1 });
  //   } else {
  //     // desktop animation with blur
  //     console.log("doing desktop animation");
  //
  //     // animate current slide to blur
  //     for (var i = 1; i < this.steps; i++) {
  //       anim.to(this.$current.children('.canvas' + i), 0.4, { opacity: 1 }, '-=0.2');
  //     }
  //
  //     // animate caption out
  //     anim.to(this.$caption, 0.5, {
  //       x: -this.width,
  //       ease: Power2.easeIn,
  //       onComplete: function() {
  //         self.updateCaption(self.curSlide);
  //       }
  //     }, '-=0.8');
  //
  //     // fade out current slide
  //     anim.to(this.$current, 0.8, { opacity: 0 }, '-=0.5');
  //
  //     // animate next slide to un-blur
  //     for (var n = this.steps-1; n > 0; n--) {
  //       anim.to(this.$next.children('.canvas' + n), 0.4, { opacity: 0 }, '-=0.2');
  //     }
  //
  //     // animate caption back in
  //     anim.to(this.$caption, 0.4, {
  //       x: 0,
  //       ease: Power2.easeOut
  //     }, '-=0.5');
  //   }
  //
  //   anim.eventCallback('onComplete', callback, params, self);
  //
  //   anim.play();
  // };
  //
  // HomeSlideshow.prototype.updateSlides = function() {
  //   var nextID = this.curSlide > 3 ? 1 : this.curSlide + 1,
  //       next_slide = this.$nav.children('[data-id=' + nextID + ']'),
  //       slide_html = '<div class="next" />';
  //
  //   // remove previous slide and update current slide
  //   this.$current.remove();
  //   this.$next.removeClass('next').addClass('current');
  //   this.$current = this.$next;
  //
  //   // create new "next slide"
  //   this.$next = $(slide_html);
  //   this.$el.append(this.$next);
  //   this.addBlurCanvas(this.$next, next_slide.data('image'));
  // };
  //
  // HomeSlideshow.prototype.updateCaption = function(i) {
  //   var slide = this.$nav.children('[data-id=' + i + ']');
  //
  //   if (i == 1) {
  //     this.$caption.addClass('landing-caption');
  //     this.$caption.children('.caption-title').html('');
  //   } else {
  //     this.$caption.removeClass('landing-caption');
  //     this.$caption.children('.caption-title').html( slide.children('.title').text() );
  //   }
  //   this.$caption.children('.caption-content').html( slide.children('.slide-caption').html() );
  // };
  //
  // HomeSlideshow.prototype.init = function(el) {
  //   var self = this,
  //       image1 = this.$nav.children('[data-id=1]').data('image'),
  //       image2 = this.$nav.children('[data-id=2]').data('image');
  //
  //   this.width = this.$el.width();
  //   this.height = this.isMobile ? 'auto' : this.$el.height();
  //
  //   this.addBlurCanvas(this.$current, image1);
  //   this.addBlurCanvas(this.$next, image2);
  //
  //   // add slide change to arrow
  //   this.$el.find('.slide-trigger').on('click', function(e) {
  //     e.preventDefault();
  //
  //     // update current slide id
  //     self.curSlide = self.curSlide > 3 ? 1 : self.curSlide + 1;
  //
  //     self.animate(self.updateSlides);
  //   });
  //
  //   // add slide change to menu links
  //   this.$nav.find('a').on('click', function(e) {
  //     e.preventDefault();
  //
  //     var li = $(this).parent(),
  //         nextSlide = li.data('id');
  //
  //     if (nextSlide == self.curSlide) return;
  //     if (nextSlide == self.curSlide + 1 || (self.curSlide == 4 && nextSlide == 1)) {
  //       self.curSlide = self.curSlide > 3 ? 1 : self.curSlide + 1;
  //       self.animate(self.updateSlides);
  //     } else {
  //       self.curSlide = nextSlide;
  //
  //       self.$next.html('');
  //       self.addBlurCanvas(self.$next, li.data('image'), true);
  //     }
  //   });
  // };
  //
  // var homeSlideshow = new HomeSlideshow($('.hero-slideshow'));
  // homeSlideshow.init();

  // troop dropdowns
  $('.troop-list').on('click', '.trigger', function(e) {
    e.preventDefault();

    var $this = $(this),
        $li = $this.parent(),
        $troop_list = $this.parents('.troop-list'),
        $dropdown = $this.siblings('.profile-content');

    // if closing the accordion, change classes and be done
    if ($li.hasClass('active')) {
      $li.removeClass('active');
      $troop_list.children('li').removeClass('inactive');
      return;
    }

    $troop_list.children('li').not($li).removeClass('active').addClass('inactive');
    $li.removeClass('inactive').addClass('active');

    // set dropdown top to correct value
    var offset_top = $li.position().top + $li.outerHeight();
    $dropdown.css('top', offset_top + 'px');

  });

  // accordions
  var $accordions = $('.accordion');
  if ($accordions.length > 0) {
    $accordions.each(function() {
      var $this = $(this),
          height = $this.children('.content').outerHeight();
      $this.data('content-height', height);
      $this.children('.content').css('height', 0);
    });

    $accordions.on('click', '.trigger', function(e) {
      e.preventDefault();

      var $accordion = $(this).parent('.accordion'),
          $content = $accordion.children('.content'),
          height = $accordion.data('content-height');

      if ($accordion.hasClass('open')){
        $accordion.removeClass('open');
        height = 0;
      } else {
        $accordion.addClass('open');
      }

      TweenLite.to($content, 0.5, {
        height: height,
        ease: Power3.easeOut
      });

    });
  }

  // responsive youtube videos
  var $video_embeds = $("iframe[src^='http://www.youtube.com'], iframe[src^='https://www.youtube.com']");
  console.log("there are videos: ", $video_embeds.length);
  if ($video_embeds.length > 0) {
    $video_embeds.each(function() {
      var $video = $(this),
          height = $video.attr('height'),
          width = $video.attr('width'),
          ratio = height/width,
          $wrapper = $('<div class="video-wrapper" style="padding-bottom:' + ratio*100 + '%" />');

      $video.wrap($wrapper);
    });
  }

  /**************************************
  ** Header Blur on Scroll
  **************************************/

  function BlurHeader(el) {
    this.$el = $(el);
  }

  BlurHeader.prototype.blurImage = function() {
    console.log("blur image");
    this.$image = this.$el.find('.image-bg');
    var self = this;

    this.$image.on('load', function() {
      // position and size image in center
      var containerWidth = self.$el.outerWidth(),
          containerHeight = self.$el.outerHeight(),
          imageWidth = self.$image.width(),
          imageHeight = self.$image.height(),
          width = 0,
          height = 0,
          offsetLeft = 0,
          offsetTop = 0;
      if ( imageWidth/imageHeight > containerWidth/containerHeight ) {
        width = containerHeight * (imageWidth/imageHeight);
        height = containerHeight;
        offsetLeft = -(width - containerWidth)/2;
      } else {
        width = containerWidth;
        height = containerWidth * (imageHeight/imageWidth);
        offsetTop = -(height-containerHeight)/2;
      }

      self.$image.css({
        'width': width + 'px',
        'height': height + 'px',
        'left': offsetLeft + 'px',
        'top': offsetTop + 'px',
        'display': 'block'
      });

      if ( Modernizr.canvas && !Modernizr.touch ) {
        // create and append canvas
        var canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        canvas.style.left = offsetLeft + 'px';
        canvas.style.top = offsetTop + 'px';
        self.$el.append(canvas);

        // set up blurry canvas
        var $window = $(window),
            transitionBlur = new TweenLite.to(canvas, 1, {opacity: 1, paused: true});

        var blurScroll = function() {
          var pos = $window.scrollTop(),
              progress = ( 1/(self.$el.height() - 80) ) * pos;

          // set tween to current progress
          if(progress >= 0 && progress <= 1) {
            transitionBlur.progress(progress);
          }
        };

        stackBlurImage( self.$image[0], canvas, 40, false );
        $window.on('scroll', blurScroll);
      }
    }).each(function() {
      // catch cached images
      if(this.complete) {
        $(this).load();
      }
    });
  };

  if ($('.blur-image').length > 0 ) {
    var page_header = new BlurHeader('.blur-image');
    page_header.blurImage();
  }

  // contact page map
  if ($('#contact-map').length > 0) {
    var map_styles = [{
        featureType:'landscape',
        elementType:'all',
        stylers:[
          { color:'#dbf1ee' },
          { visibility:'on' }
        ]
      }, {
        featureType:'water',
        elementType:'geometry.fill',
        stylers:[
          { color:'#b4d7e6' },
          { visibility:'on' }
        ]
      }, {
        featureType:'road.highway',
        elementType:'geometry.fill',
        stylers:[
          { color:'#69bfb2' },
          { visibility:'on' }
        ]
      }, {
        featureType:'road.highway',
        elementType:'geometry.stroke',
        stylers:[
          { color:'#ffffff' },
          { visibility:'on' }
        ]
      }, {
        featureType:'road.arterial',
        elementType:'all',
        stylers:[
          { color:'#ffffff' },
          { visibility:'simplified' }
        ]
      }, {
        featureType:'poi.park',
        elementType:'geometry.fill',
        stylers:[
          { color:'#bcca7f' },
          { visibility:'on' }
        ]
      }, {
        featureType:'road.local',
        elementType:'all',
        stylers:[
          { color:'#d6ebe8' },
          { visibility:'off' }
        ]
      }, {
        featureType:'poi.attraction',
        elementType:'geometry.fill',
        stylers:[
          { color:'#cce5e1' },
          { visibility:'on' }
        ]
      }, {
        featureType:'poi.place_of_worship',
        elementType:'geometry.fill',
        stylers:[
          { color:'#cce5e1' },
          { visibility:'on' }
        ]
      }, {
        featureType:'poi.government',
        elementType:'geometry.fill',
        stylers:[
          { color:'#cce5e1' },
          { visibility:'on' }
        ]
      }, {
        featureType:'poi.school',
        elementType:'geometry.fill',
        stylers:[
          { color:'#cce5e1' },
          { visibility:'on' }
        ]
      }, {
        featureType:'poi.business',
        elementType:'geometry.fill',
        stylers:[
          { color:'#bcd7d2' },
          { visibility:'on' }
        ]
      }, {
        featureType:'poi.medical',
        elementType:'geometry',
        stylers:[
          { color:'#e5fbf8' },
          { visibility:'off' }
        ]
      }, {
        featureType:'road',
        elementType:'labels',
        stylers:[
          { hue:'#ffffff' },
          { saturation:-100 },
          { lightness:100 },
          { visibility:'off' }
        ]
      }, {
        featureType:'transit',
        elementType:'labels',
        stylers:[
          { hue:'#ffffff' },
          { saturation:0 },
          { lightness:100 },
          { visibility:'off' }
        ]
      }, {
        featureType:'landscape.man_made',
        elementType:'geometry',
        stylers:[
          { color:'#e6faf8' },
          { visibility:'off' }
        ]
      }, {
        featureType:'administrative.neighborhood',
        elementType:'labels.text.fill',
        stylers:[
          { color:'#42454c' },
          { visibility:'on' }
        ]
      }, {
        featureType:'road.local',
        elementType:'all',
        stylers:[
          { color:'#cbe7e3' },
          { visibility:'simplified' }
        ]
      }
    ];
    var office_loc = new google.maps.LatLng(30.383294, -97.743659),
        marker_image = new google.maps.MarkerImage('/images/map-marker.png', null, null, null, new google.maps.Size(100,130));
    var map = new google.maps.Map(document.getElementById('contact-map'), {
      zoom: 14,
      center: office_loc,
      'styles': map_styles,
      scrollwheel: false
    });

    var office_marker = new google.maps.Marker({
      map: map,
      animation: google.maps.Animation.DROP,
      position: office_loc,
      icon: marker_image
    });
    office_marker.setMap(map);
  }

  // shoot bananas from the footer
  function shoot_bananas() {
    console.log("shooting bananas");
    var a = 0.8, // vertical accelleration
        num_bananas = Math.ceil(Math.random() * 5),
        pos = [0, -20],
        $banana_triangle = $('.bananas');

        console.log('num_bananas', num_bananas);

    // create some bananas and shoot them
    for (var i = 0; i < num_bananas; i++) {
      var banana = $('<div class="shooting-banana" />'),
          vx = (0.5 - Math.random()) * 10, // horizontal velocity, can be positive or negative
          vy = -Math.random() * 20; // initial vertical velocity

      console.log("initial x velocity:", vx + ", initial y velocity:", vy);
      $banana_triangle.append(banana);
      update_banana_pos(banana, pos, vx, vy, a);
    }
  }

  if (!Modernizr.touch) {
    $('.bananas').on('mouseenter', shoot_bananas);
  }
  $('.back-to-top').on('click', function(e) {
    e.preventDefault();
    window.scrollTo(0,0);
  });

  function update_banana_pos(banana, p0, vx, vy, a) {
    // we're under the assumption that 1 time unit has passed each iteration
    var posX = p0[0] + vx,
        posY = p0[1] + vy,
        transform = 'matrix(1, 0, 0, 1, '+ posX + ', '+ posY +')';

    // update velocity
    vy += a;

    // set the transform
    banana[0].style.webkitTransform = transform;
    banana[0].style.MozTransform = transform;
    banana[0].style.msTransform = transform;
    banana[0].style.OTransform = transform;
    banana[0].style.transform = transform;

    // if posX is greater than 20px below, keep going
    if ( posY < 20) {
      requestAnimationFrame(function() {
        update_banana_pos(banana, [posX, posY], vx, vy, a);
      });
    } else {
      banana.remove();
    }
  }

  $('.news-load-more').on('click', function(e) {
    e.preventDefault();

    $.getJSON('/latest-news-ajax/?page='+(news_current_page + 1), function(data) {
      $.each(data.articles, function(key, article) {
        block = $('.news-item').last().parent().clone();

        block.find('.date').html( article.publish_on_month+'.'+article.publish_on_day+'<span>'+article.publish_on_year+'</span>' );
        block.find('a').attr('href', article.url).attr('title', article.titlel).html(article.title);

        $('.news-item').last().parent().after(block);
      });

      if((news_current_page + 1) === news_total_pages) {
        $('.news-load-more').hide();
      } else {
        news_current_page++;
      }
    });
  });

  $('.form-newsletter form').on('submit', function(e) {
    e.preventDefault();

    $.getJSON('/mailchimp-subscribe/?email='+encodeURIComponent($('.form-newsletter input[name=email]').val()),function(data){
      if(data.status === 'passed') {
        $('.form-newsletter .subscribe-status').addClass('success');
        $('.form-newsletter .form-fields').hide();
      } else {
        $('.form-newsletter .subscribe-error').show();
      }
    });

    return false;
  });

  // erg. Crazy 404 page.
  if ($('#flowchart404').length > 0 && !Modernizr.touch && Modernizr.mq('only screen and (min-width:' + medium_break + 'px)')) {
    var flowchart = new Image(),
        container = document.getElementById('flowchart404');
    flowchart.onload = function() {
      container.appendChild(flowchart);
      container.className = 'loaded';
    };
    flowchart.src = '/images/flowchart404.png';
  }

  $('.equal-height').each(function() {
    var max_height = 0,
        children = $(this).children();

    children.each(function() {
      if(this.clientHeight > max_height) {
        max_height = this.clientHeight;
        console.log(max_height);
      }
    });

    children.each(function() {
      $(this).height(max_height-parseInt($(this).css('padding-top'))-parseInt($(this).css('padding-bottom')));
    });
  });

  $(window).on('resize', function() {
    $('.equal-height').each(function() {
      var max_height = 0,
          children = $(this).children();

      children.each(function() {
        $(this).height('auto');
      });

      children.each(function() {
        if(this.clientHeight > max_height) {
          max_height = this.clientHeight;
        }
      });

      children.each(function() {
        $(this).height(max_height-parseInt($(this).css('padding-top'))-parseInt($(this).css('padding-bottom')));
      });
    });
  });

  // Wait for window load so that the SVG has been loaded
  $(window).on('load', function() {
    if(typeof current_service_menu !== 'undefined') {
      var a = document.getElementById("services-menu-svg");
      // Get the SVG document inside the Object tag
      var svgDoc = a.contentDocument;
      // Get one of the SVG items by ID;
      var svgItem = svgDoc.getElementById("menu-"+current_service_menu);

      if(svgItem) {
        svgItem.classList.add('active');
      }
    }
  });

}(jQuery));
