jQuery(document).ready(function($) {
	var protocol = "http";
	if (document.location.protocol == "https:" || window.location.protocol == "https:") {
		protocol = "https";
	}
	if ($('div[class^=gridContainer-]').length > 0) {
		//$(window).on('load', function() {
			$(".brick img").lazyLoadXT();
			$(window).scrollTop($(window).scrollTop() + 1);
			setTimeout(function() {
				$(window).scrollTop($(window).scrollTop() - 1);
			}, 1500)
			$('div[class^=gridContainer-] .brick img').lazyLoadXT();
			$('.brick').each(function() {
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
					//$(this).width(w * cW).height(h * cH);
					$(this).attr({'data-width': w * cW});
					$(this).attr({'data-height': h * cH});
					var fS = (w * cW) * (14 / 302.5);
					var fSs = (w * cW) * (14 / 302.5) + 6;
					var lH = fS * 3 / 2;
					$(this).find('.name p').css({'font-size' : fS + 'px', 'line-height' : lH + 'px'});
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

			$(document).on('scroll', function() {
				$(".videosListFront.load-on-scroll-to").each(function(index) {
					if (!$(this).hasClass('loaded')) {
						if ($(this).offset().top < $(window).scrollTop() + $(window).height() - 200) {
							$(this).addClass("loaded");
							wall[index].fitWidth();
						}
					}
				})
			})

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

				if (!$(this).hasClass('load-on-scroll-to')) {
					wall[index].fitWidth();
				}

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
		//})
	}

	if ($('.bxslider').length > 0) {
		//$(window).on("load", function() {
			var playing = [];
			var duration = [];
			var pagination = [];
			var slider = [];
			var w = [];
			var h = [];
			var titleFS = [];
			var nextFS = [];
			var nextHW = [];
			var onTFZ  = [];
			$(".bxslider").each(function(index) {
				var i = index;
				var that = $(this);
				$('.bxslider').fadeIn();
				playing[i] = false;
				duration[i] = parseInt(that.data('duration'));
				if(parseInt(that.data('pagination')))
					pagination[i] = true;
				else
					pagination[i] = false;
				slider[i] = that.bxSlider({
					pager        : pagination[i],
					auto         : true,
					stopAuto     : false,
					pause        : duration[i],
					onSlideAfter : function() {
						that.find('.image-slide').each(function() {
							$(this).find('.video td').html('');
							$(this).find('.video').hide();
							$(this).find('.text').show();
						})
						that.find('.image-slide').removeClass("no-back");
						slider[i].startAuto(false);
						playing[i] = false;
					}
				});

				w[i] = that.find(".image-slide").width();
				h[i] = w[i] * 9 / 16;
				nextHW[i] = ((35 - 60) / (350 - 1200)) * (w[i] - 1200) + 60;
				if (nextHW[i] < 35) {
					nextHW[i] = 35;
				}
				if (nextHW[i] > 60) {
					nextHW[i] = 60;
				}
				nextFS[i] = ((25 - 36) / (350 - 1200)) * (w[i] - 1200) + 36;
				if (nextFS[i] < 35) {
					nextFS[i] = 35;
				}
				if (nextFS[i] > 60) {
					nextFS[i] = 60;
				}
				that.closest('.bx-wrapper').find('.bx-controls-direction i').css({"font-size" : nextFS[i]+"px", "padding-top" : ((nextHW[i] - nextFS[i]) / 2)+"px"});
				titleFS[i] = ((13 - 25) / (350 - 1200)) * (w[i] - 1200) + 25;
				if (titleFS[i] < 13) {
					titleFS[i] = 13;
				}
				if (titleFS[i] > 25) {
					titleFS[i] = 25;
				}

				that.find('.parent').width(w[i] * 0.3).height(w[i] * 0.3 * 9 / 16);
				onTFZ[i] = (10 / 123) * (that.find(".image-slide .parent").width() - 360) + 40;
				that.find('.text p').css({"font-size": onTFZ[i]+"px", "line-height": (onTFZ[i] * 8 / 5) + "px"});
				that.find('.parent').each(function() {
					if ($(this).height() > w[i] * 0.3 * 9 / 16) {
						parentW = (4 / 3) * Math.sqrt($(this).height() * $(this).width());
						$(this).width(parentW).height(parentW * 9 / 16);
					}
				})

				that.find('.slideTitle').css("font-size", titleFS[i]+"px");

				that.closest('.bx-wrapper').find('.bx-controls-direction i').outerWidth(nextHW[i]).outerHeight(nextHW[i]);
				that.find(".image-slide").height(h[i]);
				that.closest(".bx-viewport").height(h[i]);
				if (parseInt(that.data('title')))
					that.closest(".bx-viewport").height(h[i] + that.find(".slideTitle").outerHeight() + 40);
				$(window).on("resize", function() {
					w[i] = that.find(".image-slide").width();
					h[i] = w[i] * 9 / 16;
					nextHW[i] = ((35 - 60) / (350 - 1200)) * (w[i] - 1200) + 60;
					if (nextHW[i] < 35) {
						nextHW[i] = 35;
					}
					if (nextHW[i] > 60) {
						nextHW[i] = 60;
					}
					that.closest('.bx-wrapper').find('.bx-controls-direction i').outerWidth(nextHW[i]).outerHeight(nextHW[i]);
					nextFS[i] = ((25 - 36) / (350 - 1200)) * (w[i] - 1200) + 36;
					if (nextFS[i] < 25) {
						nextFS[i] = 25;
					}
					if (nextFS[i] > 36) {
						nextFS[i] = 36;
					}
					that.closest('.bx-wrapper').find('.bx-controls-direction i').css({"font-size" : nextFS[i]+"px", "padding-top" : ((nextHW[i] - nextFS[i]) / 2)+"px"});
					titleFS[i] = ((13 - 25) / (350 - 1200)) * (w[i] - 1200) + 25;
					if (titleFS[i] < 13) {
						titleFS[i] = 13;
					}
					if (titleFS[i] > 25) {
						titleFS[i] = 25;
					}

					that.find('.slideTitle').css("font-size", titleFS[i]+"px");
					
					that.find('.parent').width(w[i] * 0.3).height(w[i] * 0.3 * 9 / 16);
					onTFZ[i] = (10 / 123) * (that.find(".image-slide .parent").width() - 360) + 40;
					that.find('.text p').css({"font-size": onTFZ[i]+"px", "line-height": (onTFZ[i] * 8 / 5) + "px"});
					that.find('.parent').each(function() {
						if ($(this).height() > w[i] * 0.3 * 9 / 16) {
							parentW = (4 / 3) * Math.sqrt($(this).height() * $(this).width());
							$(this).width(parentW).height(parentW * 9 / 16);
						}
					})
					
					that.find(".image-slide").height($(".bx-wrapper .image-slide").width() * 9 / 16);
					that.closest(".bx-viewport").height(h[i]);
					if (parseInt(that.data('title')))
						that.closest(".bx-viewport").height(h[i] + that.find(".slideTitle").outerHeight() + 40);
					that.find(".image-slide td iframe").attr({"width":w[i], "height" : h[i]})
				})

				that.find('.image-slide').on('mouseenter', function() {
					slider[i].stopAuto(false);
				})

				that.find('.image-slide').on('mouseleave', function() {
					if (!playing[i]) {
						slider[i].startAuto(false);
					}
				})

				that.find('.image-slide .parent').on('click', function() {
					var thats = $(this).closest('.image-slide');
					var id    = thats.data('id');
					var w     = thats.width();
					var h     = w * 9 / 16;
					slider[i].stopAuto(false);
					playing[i] = true;
					thats.find('.text').hide();
					if (thats.hasClass('vimeo'))
						thats.find('.video td').html('<iframe src="' + protocol + '://player.vimeo.com/video/' + id + '?title=1&amp;byline=1&amp;portrait=1;autoplay=1;" width="' + w + '" height="' + h + '" frameborder="0"></iframe>');
					else
						thats.find('.video td').html('<iframe type="text/html" src="' + protocol + '://www.youtube.com/embed/' + id + '?autoplay=1" frameborder="0"  width="' + w + '" height="' + h + '" allowfullscreen></iframe>');
					thats.find('.video').show();
					var iframe = thats.find('td iframe')[0];
				    var player = $f(iframe);
				    player.addEvent('ready', function() {
				        player.addEvent('finish', onFinish);
				        player.addEvent('playProgress', onPlayProgress);
				    });

				    function onFinish(id) {
				        playing[i] = false;
				        slider[i].goToNextSlide();
				    }

				    function onPlayProgress(data, id) {
				        if (!thats.hasClass("no-back")) {
				        	thats.addClass("no-back");
				        }
				    }

				})

				$(document).on('mouseenter', '.bx-pager-item', function() {
					var index = $(this).find('a').data('slide-index');
					var that = $(this);
					that.closest('.bx-wrapper').find('.image-list').each(function() {
						if ($(this).find('.image-slide').data('index') == index) {
							var bg = $(this).find('.image-slide').css('background-image');
		        			bg = bg.replace('url(','').replace(')','');
		        			var offset = that.offset();
		        			that.closest('.bx-wrapper').next().find("img").attr("src", bg).css({'width': '100px', 'position': 'absolute', 'height' : (900/16)+"px", "border": "8px solid white"});
		        			that.closest('.bx-wrapper').next().find("img").css({"left" : (offset.left - 45), "top" : (offset.top - (900/16))});
		        			that.closest('.bx-wrapper').next().find("img").stop().fadeIn(500);
						}
					})
				})

				$(document).on('mouseleave', '.bx-pager-item', function() {
					$(this).closest('.bx-wrapper').next().find("img").stop().fadeOut(300);
				})
			})
		//})
	}

})