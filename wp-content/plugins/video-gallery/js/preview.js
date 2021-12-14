(function($) {
	var protocol = "http";
	if (document.location.protocol == "https:" || window.location.protocol == "https:") {
		protocol = "https";
	}
	if ($('div[class^=gridContainer-]').length > 0) {
		$(window).scrollTop($(window).scrollTop() + 1);
		setTimeout(function() {
			$(window).scrollTop($(window).scrollTop() - 1);
		}, 1500)
		$('div[class^=gridContainer-] .brick').each(function() {
			var that = $(this);
			var parent = $(this).find(".parent");
			if (that.hasClass('vimeo')) {
				that.magnificPopups({
				    items: {
				      src: protocol + '://vimeo.com/' + that.data('id')
				    },
				    type: 'iframe'
				});
			}
			else {
				that.magnificPopups({
				    items: {
				      src: protocol + '://www.youtube.com/watch?v=' + that.data('id')
				    },
				    type: 'iframe'
				});
			}
		})
		$('div[class^=gridContainer-]').fadeIn();
		var videoWidthBlock = $('div[class^=gridContainer-]').data('videowidthblock');
		var wall = [];
		function setBlocksDimensions(selector) {
			selector.find('.brick').each(function() {
				var cW = 0;
				var tW = selector.width();
				if (tW < videoWidthBlock) {
					cW = tW;
				}
				else {
					var o = parseInt(tW / videoWidthBlock);
					cW = tW / (o + 1);
				}
				cH = cW * 9 / 16;

				var w = 1;
				var h = 1;
				if ($(this).hasClass('highlight') && tW >= videoWidthBlock) {
					w = 2;
					h = 2;
				}
				$(this).attr({'data-width': w * cW});
				$(this).attr({'data-height': h * cH});
				var fS = (w * cW) * (14 / 302.5);
				var fSs = (w * cW) * (14 / 302.5) + 6;
				var lH = fS * 3 / 2;
				if ($(this).find('.parent').hasClass('with-play')) {
					$(this).find('.name p').css({'font-size' : (3 * fS) + 'px', 'line-height' : lH + 'px'});
				}
				else {
					$(this).find('.name p').css({'font-size' : fS + 'px', 'line-height' : lH + 'px'});
				}
				$(this).find('.parent').width($(this).width() * 0.6);
				$(this).find('.parent').height($(this).find('.parent').width() * 9 / 16);
				$(this).find('.name p strong').css({'font-size' : fSs + 'px'});
				var parentW;
				if ($(this).find('.parent').height() > $(this).height() * 0.6) {
					parentW = (4 / 3) * Math.sqrt($(this).find('.parent').height() * $(this).find('.parent').width());
					$(this).find('.parent').width(parentW).height(parentW * 9 / 16);
				}

			})
		}
		$(".videosListFront").each(function(index) {
			var videoSpacingY = $(this).closest('div[class^=gridContainer-]').data('videospacingy');
			var videoSpacingX = $(this).closest('div[class^=gridContainer-]').data('videospacingx');
			var animation_delay = $(this).closest('div[class^=gridContainer-]').data('animationdelay');
			$(this).addClass('nbr-' + index);
			var id = $(this).data('id');
			var that = $(this);
			var i = index;
			setBlocksDimensions(that);
			wall[index] = new freewall(".nbr-" + index);
			wall[index].reset({
				selector: '.brick',
				animate: false,
				delay: animation_delay,
				draggable: false,
				gutterY: videoSpacingY,
				gutterX: videoSpacingX,
				cellW: function() {
					var cW = 0;
					var tW = that.width();
					if (tW < videoWidthBlock) {
						cW = that.width();
					}
					else {
						var o = parseInt(that.width() / videoWidthBlock);
						cW = that.width() / (o + 1);
					}
					setBlocksDimensions(that);
					return cW;
				},
				cellH: function() {
					var cW = 0;
					var tW = that.width();
					if (tW < videoWidthBlock) {
						cW = tW;
					}
					else {
						var o = parseInt(tW / videoWidthBlock);
						cW = tW / (o + 1);
					}
					cH = cW * 9 / 16;
					return cH;
				},
				onResize: function() {
					wall[index].fitWidth();
				},
				onBlockFinish: function() {
					$('div[class^=gridContainer-] .brick img').lazyLoadXT();
					$(window).scrollTop($(window).scrollTop() + 1);
					$(window).scrollTop($(window).scrollTop() - 1);
				},
				onComplete: function() {
					var parentW;
					$('.videosListFront .brick').each(function() {
						$(this).find('.parent').width($(this).width() * 0.6);
						$(this).find('.parent').height($(this).find('.parent').width() * 9 / 16);
						if ($(this).find('.parent').height() > $(this).height() * 0.6) {
							parentW = (4 / 3) * Math.sqrt($(this).find('.parent').height() * $(this).find('.parent').width());
							$(this).find('.parent').width(parentW).height(parentW * 9 / 16);
						}
					})
				}
			});

			wall[index].fitWidth();


			var filters = [];
			filters[i] = [];

			var filtersContainer = [];
			filtersContainer[i] = [];

			filtersContainer[i] = that.parent().find('.tags');

			filtersContainer[i].find('div[class^="check-"] span').on('click', function() {
				var input = $(this).parent().find('input');
				if (!input.is(':checked')) {
					input.attr('checked', true);
				}
				else {
					input.attr('checked', false);
				}
				input.trigger('change');
			})

			filtersContainer[i].find('div[class^="check-"] input[type="checkbox"]').change(function(e) {
				var filter = $(this).closest('div[class^="check-"]').data('filter');
				if (filter != 'all-tags') {
				    if (!$(this).is(':checked')) {
			            var index = filters[i].indexOf(filter);
			            if (index > -1) {
						    filters[i].splice(index, 1);
						}
			        }
			        else {
			        	filters[i].push(filter);
			        }
			        var filtersSelector = '';
			        $.each(filters[i], function(index, value) {
			        	if (index == 0)
							filtersSelector += value;
						else
							filtersSelector += ',' + value;
			        });
			        filtersContainer[i].find('.check-all-tags input').attr('checked', false);
			        filtersContainer[i].find('.check-all-tags input').parent().removeClass('checked');
			        wall[i].filter(filtersSelector);
			    }
			    else {
			    	filters[i] = [];
			    	wall[i].unFilter();
			    	filtersContainer[i].find('div[class^="check-"] input[type="checkbox"]').each(function() {
			    		if ($(this).closest('div[class^="check-"]').data('filter') != 'all-tags')
			    			$(this).attr('checked', false);
			    			$(this).parent().removeClass('checked');
			    	})
			    }
			    var nbrCheck = 0;
			    filtersContainer[i].find('div[class^="check-"] input[type="checkbox"]').each(function() {
		    		if ($(this).attr('checked'))
		    			nbrCheck++;
		    	})
		    	if (nbrCheck == 0) {
		    		filtersContainer[i].find('.check-all-tags input').attr('checked', true);
		    		filtersContainer[i].find('.check-all-tags input').parent().addClass("checked");
		    		filters[i] = [];
		    		wall[i].unFilter();
		    	}
			});

			$('.tags input[type="checkbox"]').on("change", function() {
				if (!$(this).is(':checked')) {
					$(this).parent().removeClass("checked");
				}
				else {
					$(this).parent().addClass("checked");
				}
			})
		})
	}

	if ($('.bxslider').length > 0) {
		$('.bxslider').fadeIn();
		var parentW;
		var playing = false;
		var duration = parseInt($('.bxslider').data('duration'));
		var pagination = false;
		var onTFZ;
		if(parseInt($('.bxslider').data('pagination')))
			pagination = true;
		var w = $(".bx-wrapper .image-slide").width();
		slider = $(".bxslider").bxSlider({
			pager        : pagination,
			auto         : true,
			stopAuto     : false,
			pause        : duration,
			onSlideAfter : function() {
				$('.image-slide').each(function() {
					$(this).find('.video td').html('');
					$(this).find('.video').hide();
					$(this).find('.text').show();
				})
				$('.image-slide').removeClass('no-back');
				slider.startAuto(false);
				playing = false;
			}
		});
		w = $(".bx-wrapper .image-slide").width();
		var h = w * 9 / 16;
		var nextHW = ((35 - 60) / (350 - 1200)) * (w - 1200) + 60;
		if (nextHW < 35) {
			nextHW = 35;
		}
		if (nextHW > 60) {
			nextHW = 60;
		}
		var nextFS = ((25 - 36) / (350 - 1200)) * (w - 1200) + 36;
		if (nextFS < 35) {
			nextFS = 35;
		}
		if (nextFS > 60) {
			nextFS = 60;
		}
		$('.bx-wrapper .bx-controls-direction i').css({"font-size" : nextFS+"px", "padding-top" : ((nextHW - nextFS) / 2)+"px", "width" : nextHW+"px", "height" : nextHW+"px"});
		var titleFS = ((13 - 25) / (350 - 1200)) * (w - 1200) + 25;
		if (titleFS < 13) {
			titleFS = 13;
		}
		if (titleFS > 25) {
			titleFS = 25;
		}
		$('.bxslider .parent').width(w * 0.3).height(w * 0.3 * 9 / 16);
		onTFZ = (10 / 123) * ($(".bx-wrapper .image-slide .parent").width() - 360) + 35;
		$('.bxslider .text p').css({"font-size": onTFZ+"px", "line-height": (onTFZ * 8 / 5) + "px"});
		$('.bxslider .parent').each(function() {
			if ($(this).height() > w * 0.3 * 9 / 16) {
				$(this).addClass('here')
				parentW = (4 / 3) * Math.sqrt($(this).height() * $(this).width());
				$(this).width(parentW).height(parentW * 9 / 16);
			}
		})
		$('.bxslider .slideTitle').css("font-size", titleFS+"px");
		//$('.bx-wrapper .bx-controls-direction i').outerWidth(nextHW).outerHeight(nextHW);
		$(".bx-wrapper .image-slide").height(h);
		$(".bx-viewport").height(h);
		if (parseInt($('.bxslider').data('title')))
			$(".bx-viewport").height(h + $(".slideTitle").outerHeight() + 40);
		$(window).on("resize", function() {
			w = $(".bx-wrapper .image-slide").width();
			h = w * 9 / 16;
			var nextHW = ((35 - 60) / (350 - 1200)) * (w - 1200) + 60;
			if (nextHW < 35) {
				nextHW = 35;
			}
			if (nextHW > 60) {
				nextHW = 60;
			}
			$('.bx-wrapper .bx-controls-direction i').outerWidth(nextHW).outerHeight(nextHW);
			var nextFS = ((25 - 36) / (350 - 1200)) * (w - 1200) + 36;
			if (nextFS < 25) {
				nextFS = 25;
			}
			if (nextFS > 36) {
				nextFS = 36;
			}
			$('.bx-wrapper .bx-controls-direction i').css({"font-size" : nextFS+"px", "padding-top" : ((nextHW - nextFS) / 2)+"px"});
			var titleFS = ((13 - 25) / (350 - 1200)) * (w - 1200) + 25;
			if (titleFS < 13) {
				titleFS = 13;
			}
			if (titleFS > 25) {
				titleFS = 25;
			}
			$('.bxslider .slideTitle').css("font-size", titleFS+"px");

			$('.bxslider .parent').width(w * 0.3).height(w * 0.3 * 9 / 16);
			onTFZ = (10 / 123) * ($(".bx-wrapper .image-slide .parent").width() - 360) + 35;
			$('.bxslider .text p').css({"font-size": onTFZ+"px", "line-height": (onTFZ * 8 / 5) + "px"});
			$('.bxslider .parent').each(function() {
				if ($(this).height() > w * 0.3 * 9 / 16) {
					parentW = (4 / 3) * Math.sqrt($(this).height() * $(this).width());
					$(this).width(parentW).height(parentW * 9 / 16);
				}
			})

			$(".bx-wrapper .image-slide").height($(".bx-wrapper .image-slide").width() * 9 / 16);
			$(".bx-viewport").height(h);
			if (parseInt($('.bxslider').data('title')))
				$(".bx-viewport").height(h + $(".slideTitle").outerHeight() + 40);
			$('.image-slide td iframe').attr({"width":w, "height" : h})
		})

		$(document).on('click', '.image-slide .parent', function() {
			var thats = $(this).closest('.image-slide');
			var id   = thats.data('id');
			w = $(".bx-wrapper .image-slide").width();
			h = w * 9 / 16;
			slider.stopAuto(false);
			playing = true;
			thats.find('.text').hide();
			if (thats.hasClass('vimeo'))
				thats.find('.video td').html('<iframe src="' + protocol + '://player.vimeo.com/video/' + id + '?title=1&amp;byline=1&amp;portrait=1;autoplay=1;" width="' + w + '" height="' + h + '" frameborder="0"></iframe>');
			else
				thats.find('.video td').html('<iframe type="text/html" src="' + protocol + '://www.youtube.com/embed/' + id + '?autoplay=1" allowfullscreen frameborder="0"  width="' + w + '" height="' + h + '"></iframe>');
			thats.find('.video').show();
			var iframe = thats.find('td iframe')[0];
		    var player = $f(iframe);

		    player.addEvent('ready', function() {
		        player.addEvent('finish', onFinish);
		        player.addEvent('playProgress', onPlayProgress);
		    });

		    function onFinish(id) {
		        playing = false;
		        slider.goToNextSlide();
		    }

		    function onPlayProgress(id) {
				if (!thats.hasClass("no-back")) {
		        	thats.addClass("no-back");
		        }
		    }
		})

		$('.image-slide').on('mouseenter', function() {
			slider.stopAuto(false);
		})

		$('.image-slide').on('mouseleave', function() {
			if (!playing) {
				slider.startAuto(false);
			}
		})
		
		$(document).on('mouseenter', '.bx-pager-item', function() {
			var index = $(this).find('a').data('slide-index');
			var that = $(this);
			$('.image-list').each(function() {
				if ($(this).find('.image-slide').data('index') == index) {
					var bg = $(this).find('.image-slide').css('background-image');
        			bg = bg.replace('url("','').replace('")','');
        			bg = bg.replace("url('",'').replace("')",'');
        			var offset = that.offset();
        			$(".pagerImage img").attr("src", bg).css({'width': '100px', 'position': 'absolute', 'height' : (900/16)+"px", "border": "8px solid white"});
        			$('.pagerImage img').css({"left" : (offset.left - $('#previewDiv').offset().left - 45), "top" : (offset.top - 120)});
        			$(".pagerImage img").stop().fadeIn(500);
				}
			})
		})

		$(document).on('mouseleave', '.bx-pager-item', function() {
			$(".pagerImage img").stop().fadeOut(300);
		})
	}

})(window.jQuery);