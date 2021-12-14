(function($) {
	$(window).on('load', function() {
		$('.preLoad').remove();
		$('#singleGrid').fadeIn(400);
		$(".secondList .videos .brick img, .ordredVids .brick img").lazyLoadXT();
		var fullWidth = $(".videosList").width();
		function getRandomInt(min, max) {
			return Math.floor(Math.random() * (max - min + 1)) + min;
		}
		function setBlocksDimensions(selector) {
			selector.find('.brick').each(function() {
				var cW = 0;
				var tW = selector.width();
				if (tW < 350) {
					cW = tW;
				}
				else {
					var o = parseInt(tW / 350);
					cW = tW / (o + 1);
				}
				cH = cW * 9 / 16;

				//$(this).width(cW).height(cH);
				$(this).attr({'data-width': cW});
				$(this).attr({'data-height': cH});
				var fS = cW * (14 / 302.5) + 2;
				var lH = fS * 3 / 2;
				$(this).find('.name p').css({'font-size' : fS + 'px', 'line-height' : lH + 'px'});
			})
		}

		var wallO = new freewall(".ordredVids");
		wallO.reset({
			selector: '.brick',
			animate: false,
			draggable: true,
			cellW: function(width) {
				$('#videosOrder .brick').width(width);
				$('#videosOrder .brick').height(75);
				$('#videosOrder .brick').attr({'data-width': width});
				$('#videosOrder .brick').attr({'data-height': 75});
				$('#videosOrder .brick img').each(function() {
					$(this).width( (13 / 9) * $('#videosOrder .brick').height());
				})
				return width;
			},
			cellH: (75),
			onResize: function() {
				wallO.fitWidth();
			},
			onComplete: function() {
				setOrder();
			}
		});

		wallO.fitWidth();
		$('#videosList .brick').width(fullWidth/3);
		$('#videosList .brick').height(fullWidth/5.3);
		var wall = new freewall(".videos");
		wall.reset({
			selector: '.brick',
			animate: true,
			draggable: false,
			cellW: function(width) {
				var cW = 0;
				var tW = $("#videosList .videos").width();
				if (tW < 350) {
					cW = $("#videosList .videos").width();
				}
				else {
					var o = parseInt($("#videosList .videos").width() / 350);
					cW = $("#videosList .videos").width() / (o + 1);
				}
				setBlocksDimensions($("#videosList .videos"));
				return cW;
			},
			cellH: function() {
				var cW = 0;
				var tW = $("#videosList .videos").width();
				if (tW < 350) {
					cW = tW;
				}
				else {
					var o = parseInt(tW / 350);
					cW = tW / (o + 1);
				}
				cH = cW * 9 / 16;
				return cH;
			},
			onResize: function() {
				wall.fitWidth();
			},
			onComplete: function() {
				setTimeout(function() {
					$(window).scrollTop($(window).scrollTop()+1);
					$(window).scrollTop($(window).scrollTop()-1);
				}, 300)
			}
		});

		wall.fitWidth();

		wall.sortBy(function(a, b) {
			return $(a).find('.name').text().toUpperCase().localeCompare($(b).find('.name').text().toUpperCase());
		});

		$(document).on("click", '#videosList .brick', function() {
			if($(this).hasClass("selected")) {
				$("#videosOrder").find(".item-"+$(this).data('id')).removeClass("chosen");
				$(this).removeClass("selected");
			}
			else {
				$("#videosOrder").find(".item-"+$(this).data('id')).addClass("chosen");
				$(this).addClass("selected");
			}
			if ($('#videosOrder .chosen').length > 0) {
				$('#singleGrid h1 a, .steps .item2, .steps .item3').removeClass("disabled");
			}
			else {
				$('#singleGrid h1 a, .steps .item2, .steps .item3').addClass("disabled");
			}
			var selectionNbr = $('#videosOrder .chosen').length;
			$('#videosOrder .selectionCount p span').html(selectionNbr)
		})

		$(document).on("click", '.sorting .order', function(){
			var order = $(this).data("order");
			$('.order').removeClass("active");
			$(this).addClass("active");
			switch (order) {
				case "alpha-desc":
					wall.sortBy(function(a, b) {
						return $(b).find('.name').text().toUpperCase().localeCompare($(a).find('.name').text().toUpperCase());
					});
					break;
				case "alpha-asc":
					wall.sortBy(function(a, b) {
						return $(a).find('.name').text().toUpperCase().localeCompare($(b).find('.name').text().toUpperCase());
					});
					break;
				case "date-asc":
					wall.sortBy(function(a, b) {
						return $(a).find('.date').text().toUpperCase().localeCompare($(b).find('.date').text().toUpperCase());
					});
					break;
				case "date-desc":
					wall.sortBy(function(a, b) {
						return $(b).find('.date').text().toUpperCase().localeCompare($(a).find('.date').text().toUpperCase());
					});
					break;
			}
		})
		
		$(document).on('click', '.selectAll', function() {
			$("#videosList .brick").each(function() {
				if ($(this).css('opacity') != 0) {
					$("#videosOrder").find(".item-"+$(this).data('id')).addClass("chosen");
					$(this).addClass("selected");
				}
			})
			if ($("#videosList .brick.selected").length > 0) {
				$('#singleGrid h1 a, .steps .item2, .steps .item3').removeClass("disabled");
			}
			else {
				$('#singleGrid h1 a, .steps .item2, .steps .item3').addClass("disabled");
			}
			var selectionNbr = $('#videosOrder .chosen').length;
			$('#videosOrder .selectionCount p span').html(selectionNbr)
		})

		$(document).on('click', '.unselectAll', function() {
			$("#videosList .brick").each(function() {
				if ($(this).css('opacity') != 0) {
					$("#videosOrder").find(".item-"+$(this).data('id')).removeClass("chosen");
					$(this).removeClass("selected");
				}
			})
			if ($("#videosList .brick.selected").length > 0) {
				$('#singleGrid h1 a, .steps .item2, .steps .item3').removeClass("disabled");
			}
			else {
				$('#singleGrid h1 a, .steps .item2, .steps .item3').addClass("disabled");
			}
			var selectionNbr = $('#videosOrder .chosen').length;
			$('#videosOrder .selectionCount p span').html(selectionNbr)
		})

		function goToOrder() {
			wallO.filter('.chosen');
			$("#videosList, #previewDiv").fadeOut(400, function() {
				$('#videosOrder').fadeIn(400);
				setTimeout(function() {
					setOrder();
					$(window).scrollTop($(window).scrollTop()+1);
					$(window).scrollTop($(window).scrollTop()-1);
				}, 420)
			})
		}

		function goToSelect() {
			$("#videosOrder, #previewDiv").fadeOut(400, function() {
				$('#videosList').fadeIn(400, function() {
					wall.fitWidth();
				});
			})
		}

		function goToPreview() {
			$(".header .steps .item").removeClass('active');
			$(".header .steps .item3").addClass('active')
			var videos    = [],
				theme      = $('input[name=theme]:checked', '#themeForm').val(),
				display_trigger = $('input[name=display-trigger]:checked').val(),
				title_box_mode = $('input[name=title-box-mode]:checked').val(),
				show_tags = $('input[name=tags]:checked', '#showTags').val(),
				duration   = $('#sliderOption .duration').val(),
				video_width   = $('#singleGrid .videoWidth input').val(),
				video_spacing_x = $('#singleGrid #videoSpacingX input').val(),
				video_spacing_y = $('#singleGrid #videoSpacingY input').val(),
				animation_delay = $('#singleGrid .CSSAnimation .animation_delay').val(),
				animation = $('#singleGrid .CSSAnimation .video-animation').val(),
				enable_css_animation = 0,
				show_title = $('input[name=showTitle]:checked', '#sliderOption .formTitle').val(),
				show_pager = $('input[name=showPage]:checked', '#sliderOption .formPage').val(),
				tags_color = $('#colorSelector2').val(),
				text_color = $('#colorSelector1').val(),
				text_color_hover = $('#colorSelector3').val(),
				box_color_initial = $('#colorSelector4').val(),
				box_color = $('#colorSelector5').val(),
				custom_css = $('.accordion.customCSS textarea').val();
			if ($('.enable_css_animation').is(':checked')) {
				enable_css_animation = 1;
			}
			setTimeout(function() {
				$('#previewDiv').fadeIn(400);
				$('#previewDiv .pContent').html('<div class="spinnerDiv"><i class="fa fa-spinner fa-spin"></i></div>');
			})
			$('#videosOrder .chosen').each(function() {
				var video = [];
				video[0]   = "/videos/" + $(this).data('id');
				video[1] = $(this).find('.rank').html();
				if ($(this).find('.isBig').is(':checked')) {
					video[2] = 1;
				}
				else {
					video[2] = 0;
				}
				videos.push(video);
			})
			$("#videosOrder, #videosList").fadeOut(400);
			$.ajax({
	            type: "POST",
	            data: {
	            	action      : "wp_video_gallery_ajax_preview_grid",
	            	gridId      : $('#gridId').val(),
	            	videos      :  videos,
	            	show_tags   : show_tags,
	            	duration    : duration,
	            	show_title  : show_title,
	            	theme       : theme,
	            	display_trigger: display_trigger,
	            	title_box_mode: title_box_mode,
	            	tags_color  : tags_color,
	            	text_color  : text_color,
	            	animation_delay  : animation_delay,
	            	animation  : animation,
	            	enable_css_animation : enable_css_animation,
	            	box_color   : box_color,
	            	box_color_initial   : box_color_initial,
	            	text_color_hover : text_color_hover,
	            	video_width : video_width,
	            	video_spacing_x : video_spacing_x,
	            	video_spacing_y : video_spacing_y,
	            	show_pager  : show_pager,
	            	custom_css  : custom_css,
	            	name        : $('#singleGrid .portfolio-title').val()
	            },
	            url: ajaxurl,
	            success: function(data){
	                $("#previewDiv .pContent").html(data);
	                $.getScript(preview_path);
	                $.ajax({
			            type: "POST",
			            data: {
			            	action     : "wp_video_gallery_ajax_get_tags_conf",
			            	gridId     : $('#gridId').val()
			            },
			            url: ajaxurl,
			            success: function(data){
			                $('.tagsModal table').html(data);
			                if (data == "no tags") {
			                	$(".accordion.tagsList").hide();
			                }
			                else if (theme != "Slider") {
			                	$(".accordion.tagsList").show();
			                }
			            }
			        });
	            }
	        });
		}

		function setOrder() {
			var test = [];
			$("#videosOrder .brick").each(function(index) {
				if ($(this).width() != 0) {
					var infos = [];
					infos['id'] = $(this).data('id');
					infos['top'] = $(this).offset().top;
					test.push(infos);
				}
			})
			test.sort(function(a, b) {
				return a['top'] - b['top']
			});
			$.each(test, function(index, value) {
				var ids = value['id'];
				$(".item-"+ids+' .rank').html(index);
			})
			$('#singleGrid h1 a').removeClass("disabled");
		}

		$(document).on("click", ".header .steps .item", function() {
			if (!$(this).hasClass('disabled')) {
				var action = $(this).data('action');
				$(".header .steps .item").removeClass('active');
				$(this).addClass('active')
				switch(action) {
					case 'order':
						goToOrder();
						break;
					case 'select':
						goToSelect();
						break;
					case 'display':
						goToPreview();
						break;
				}
			}
		})

		var filters = [];
		if ($('input[name=andOr]:checked', '#andOrFilter').val() == 0) {
			var filterType = "AND";
		}
		else {
			var filterType = "OR";
		}

		$('div[class^="check-"] input[type="checkbox"]').each(function() {
			var filter = $(this).closest('div[class^="check-"]').data('filter');
			if (filter != 'all-tags') {
			    if ($(this).is(':checked')) {
		        	filters.push(filter);
		        }
		    }
		})

		if (filters.length > 0) {
			var filtersSelectorI = '';
		    $.each(filters, function(index, value) {
		    	if (filterType == "AND") {
					filtersSelectorI += value;
		    	}
		    	else {
		    		if (index == 0) {
		    			filtersSelectorI += value;
		    		}
		    		else {
		    			filtersSelectorI += "," + value;
		    		}
		    	}
		    });
		    wall.filter(filtersSelectorI);
		    setTimeout(function() {
		    	var resultsNbrI = 0;
			    $('#videosList .brick').each(function() {
			    	if ($(this).css('opacity') > 0.7)
			    		resultsNbrI++;
			    })
			    $('#videosList .resultsCount p span').html(resultsNbrI);
		    }, 500)
		}

		$('div[class^="check-"] input[type="checkbox"]').change(function(e) {
			var filter = $(this).closest('div[class^="check-"]').data('filter');
			if (filter != 'all-tags') {
			    if (!$(this).is(':checked')) {
		            var index = filters.indexOf(filter);
		            if (index > -1) {
					    filters.splice(index, 1);
					}
		        }
		        else {
		        	filters.push(filter);
		        }
		        var filtersSelector = '';
		        $.each(filters, function(index, value) {
		        	if (filterType == "AND") {
						filtersSelector += value;
		        	}
		        	else {
		        		if (index == 0) {
		        			filtersSelector += value;
		        		}
		        		else {
		        			filtersSelector += "," + value;
		        		}
		        	}
		        });
		        wall.filter(filtersSelector);
		        $(this).closest('.filtersList').find('.check-all-tags input').attr('checked', false);
		    }
		    else {
		    	filters = [];
		    	wall.unFilter();
		    	$(this).closest('.filtersList').find('div[class^="check-"] input[type="checkbox"]').each(function() {
		    		if ($(this).closest('div[class^="check-"]').data('filter') != 'all-tags')
		    			$(this).attr('checked', false);
		    	})
		    }
		    var i = 0;
		    $(this).closest('.filtersList').find('div[class^="check-"] input[type="checkbox"]').each(function() {
	    		if ($(this).attr('checked'))
	    			i++;
	    	})
	    	if (i == 0) {
	    		$('.check-all-tags input').attr('checked', true);
	    		filters = [];
	    		wall.unFilter();
	    	}

	    	var toSend = [];
	    	$.each(filters, function(index, value) {
	    		toSend[index] = value;
	    	})

	    	$.ajax({
	            type: "POST",
	            data: {
	            	action     : "wp_video_gallery_ajax_save_search_tags",
	            	filters    : toSend,
	            	filterType : filterType,
	            	gridId     : $('#gridId').val()
	            },
	            url: ajaxurl,
	            success: function(data) {
	            }
	        });

		    setTimeout(function() {
		    	var resultsNbr = 0;
			    $('#videosList .brick').each(function() {
			    	if ($(this).css('opacity') > 0.7)
			    		resultsNbr++;
			    })
			    $('#videosList .resultsCount p span').html(resultsNbr);
		    }, 500)
		});

		$("input[name=andOr]:radio").change(function () {
			var val = $('input[name=andOr]:checked', '#andOrFilter').val();
			if (val == 0) {
				filterType = "AND";
			}
			else {
				filterType = "OR";
			}
			var filtersSelector = '';
	        $.each(filters, function(index, value) {
	        	if (filterType == "AND") {
					filtersSelector += value;
	        	}
	        	else {
	        		if (index == 0) {
	        			filtersSelector += value;
	        		}
	        		else {
	        			filtersSelector += "," + value;
	        		}
	        	}
	        });
	        wall.filter(filtersSelector);
	    	setTimeout(function() {
		    	var resultsNbr = 0;
			    $('#videosList .brick').each(function() {
			    	if ($(this).css('opacity') > 0.7)
			    		resultsNbr++;
			    })
			    $('#videosList .resultsCount p span').html(resultsNbr);
		    }, 500)
		})

		$("input[name=tags]:radio").change(function () {
			if($(this).val() == 0) {
				$('.tagsColor, .chooseTags').fadeOut(300);
			}
			else {
				$('.tagsColor, .chooseTags').fadeIn(300);
			}
			goToPreview();
		})

		$("input[name=showTitle]:radio, input[name='display-trigger'], input[name='title-box-mode']").change(function () {
			goToPreview();
		})

		$("input[name=showPage]:radio").change(function () {
			goToPreview();
		})

		$('#sliderOption .duration').on("change", function() {
			goToPreview();
		})

		$('#singleGrid .videoWidth input, #singleGrid .videoWidth select, .accordion.customCSS textarea').on("change", function() {
			goToPreview();
		})

		if ($('.brick.chosen').length && !$('#searching').length) {
			goToPreview();
		}

		$(document).on('keypress', '#singleGrid .videoWidth input', function(event) {
			if (event.keyCode == 13) {
	            $(this).blur();
	        }
	    });

		$("input[name=theme]:radio").change(function () {
			if($(this).val() == 'Slider') {
				$('.tagsList, .configList, .loadingBox').fadeOut(300, function() {
					$('.sliderList').fadeIn(300);
				});
			}
			else {
				$('.sliderList').fadeOut(300, function() {
					$('.tagsList, .configList, .loadingBox').fadeIn(300);
				});
			}
			goToPreview();
		})

		$(document).on('click', '#singleGrid h1 .page-title-action, .front-page-demo-save', function(e) {
			e.preventDefault();
			if (!$(this).hasClass('disabled')) {
				var videos     = [],
					that       = $(this)
					html       = $(this).html(),
					show_tags  = $('input[name=tags]:checked', '#showTags').val(),
					duration   = $('#sliderOption .duration').val(),
					show_title = $('input[name=showTitle]:checked', '#sliderOption .formTitle').val(),
					theme      = $('input[name=theme]:checked', '#themeForm').val();
				that.html("<i class='fa fa-spin fa-spinner'></i>").addClass("disabled");
				$('#videosOrder .chosen').each(function() {
					var video = [];
					video[0]   = "/videos/" + $(this).data('id');
					video[1] = $(this).find('.rank').html();
					if ($(this).find('.isBig').is(':checked')) {
						video[2] = 1;
					}
					else {
						video[2] = 0;
					}
					videos.push(video);
				})
				$.ajax({
		            type: "POST",
		            data: {
		            	action     : "wp_video_gallery_ajax_save_grid",
		            	videos     :  videos,
		            	gridId     : $('#gridId').val(),
		            	show_tags  : show_tags,
		            	duration   : duration,
		            	show_title : show_title,
		            	theme      : theme,
		            	name       : $('#singleGrid .portfolio-title').val()
		            },
		            url: ajaxurl,
		            success: function() {
		                that.html(html).removeClass("disabled");
	                }
	            });
			}
		})

		$(document).on("click", '#videosOrder .brick .bigContainer .fa-times', function() {
			var id = $(this).closest('.brick').data('id');
			$('#videosOrder .item-'+id).removeClass('chosen');
			$('#videosList .item-'+id).removeClass('selected');
			$('#videosOrder .item-'+id+' .isBig').attr('checked', false);
			wallO.filter('.chosen');
			setOrder();
			var selectionNbr = $('#videosOrder .chosen').length;
			$('#videosOrder .selectionCount p span').html(selectionNbr)
		})

		$(".colorSelector").spectrum({
			showAlpha: true,
			preferredFormat: "rgb"
		});

	    $(document).on('click', '.chooseTags button', function() {
			$('.tagsModal').fadeIn(400);
		})

		$(document).on('click', '.tagsModal .cancel', function() {
			$('.tagsModal').fadeOut(400);
		})

		$(document).on('click', '.tagsModal .save', function() {
			var tagsArray = [];
			$('.tagsModal td').each(function(index) {
				if ($(this).find('input').is(':checked'))
					var check = 1;
				else
					var check = 0;
				var name = $(this).find('span').html();
				var tag = [];
				tag[0]    = name;
				tag[1] = check;
				tagsArray[index] = tag;
			});
			
			$.ajax({
	            type: "POST",
	            data: {
	            	action     : "wp_video_gallery_ajax_set_seleted_tags",
	            	tags       :  tagsArray,
	            	gridId     : $('#gridId').val()
	            },
	            url: ajaxurl,
	            success: function(data) {
	                $('.tagsModal').fadeOut(400);
	                goToPreview();
	            }
	        });
		})

		$(document).on("click", '.refreshVideos p', function() {
			if (!$(this).hasClass("disabled")) {
				$(this).find('i').addClass("fa-spin");
				$(this).addClass("disabled");
				$.ajax({
		            type: "POST",
		            data: {
		            	action    : "wp_video_galleryAjaxRefreshVideos"
		            },
		            url: ajaxurl,
		            success: function(data){
		                location.reload();
		            }
		        });
			}	
		})

		var saveSearchQuery = function() {
			var search_query = $('.searchDiv input').val();
			$('.searchDiv button .fa').removeClass("fa-search").addClass('fa-spinner').addClass('fa-spin');
			$('.searchDiv button').addClass('disabled');
			$('.searchDiv input').attr('disabled', true);
			$.ajax({
	            type: "POST",
	            data: {
	            	action : "wp_video_galleryAjaxRefreshSearchQuery",
	            	value  : search_query,
	            	gridId : $('#gridId').val()
	            },
	            url: ajaxurl,
	            success: function(data){
	            	window.location.href = location.href + "&action=search";
	            }
	        });
		}

		$(document).on('click', '#singleGrid .searchDiv button', function() {
			if (!$(this).hasClass('disabled'))
				saveSearchQuery();
		})

		$(document).on('keypress', '#singleGrid .searchDiv input', function(event) {
			if (event.keyCode == 13) {
	            saveSearchQuery();
	        }
	    });

	    $('#singleGrid .filtersList h3 i').magnificPopup({
	  		items: {
	      		src: '#account-popup',
      			type: 'inline'
		  	}
		});

		$(document).on('click', '#account-popup .cancel', function() {
			$.magnificPopup.close();
		})

		$(document).on('click', '#previewDiv .accordion h3', function() {
			var par = $(this).closest('.accordion');
			if (par.hasClass('open'))
				par.removeClass('open').addClass('close');
			else {
				$('.accordion.open').removeClass('open').addClass('close');
				par.removeClass('close').addClass('open');
			}
		})

		$(document).on('click', '.apply_colors', function() {
			goToPreview();
		})

	})

	if ($('.bxslider').length > 0) {
		$(".bxslider").bxSlider({
			pager        : false,
			auto         : true,
			autoHover    : true,
			onSlideAfter : function() {
				$('.image-slide').each(function() {
					$(this).find('td').html('<i></i>');
				})
			}
		});
		$(".bx-wrapper .image-slide").height($(".bx-wrapper .image-slide").width() * 9 / 16);
		$(".bx-viewport").height($(".bx-wrapper .image-slide").width() * 9 / 16 + 66);
		$(window).on("resize", function() {
			$(".bx-wrapper .image-slide").height($(".bx-wrapper .image-slide").width() * 9 / 16);
			$(".bx-viewport").height($(".bx-wrapper .image-slide").width() * 9 / 16 + 66);
		})
		$('.image-slide').on('click', function() {
			var that = $(this);
			var id   = that.data('id');
			var w    = that.width();
			var h    = w * 9 / 16;
			var protocol = "http";
			if (document.location.protocol == "https:" || window.location.protocol == "https:") {
				protocol = "https";
			}
			if ($(this).hasClass('vimeo'))
				$(this).find('td').html('<iframe src="' + protocol + '://player.vimeo.com/video/' + id + '?title=1&amp;byline=1&amp;portrait=1;autoplay=1;" width="' + w + '" height="' + h + '" frameborder="0"></iframe>');
			else
				$(this).find('td').html('<iframe type="text/html" src="' + protocol + '://www.youtube.com/embed/' + id + '?autoplay=1" allowfullscreen frameborder="0"  width="' + w + '" height="' + h + '"></iframe>');
		})
	}

	if ($('.removeGrid').length) {
		$('.removeGrid').each(function() {
			var that = $(this);
			var name = that.closest('.name').find('.titleLink').html();
			var text = that.data('text');
			var id   = that.data('id');
			var message = text + name + " ?";
			that.on("click", function() {
				deleteGrid(message, id)
			})
		})

		function deleteGrid(message, id) {
		    if (confirm(message)) {
		        $.ajax({
		            type: "POST",
		            data: {
		            	action    : "wp_video_gallery_ajax_remove_grid",
		            	gridId    : id
		            },
		            url: ajaxurl,
		            success: function(data){
		                location.reload();
		            }
		        });
		    }
		    return false;
		}
	}

	$(document).on("click", "#vimeoMajbaGrids .duplicate-grid", function(e) {
		var that = $(this),
			that_html = that.html(),
			postdata = {
				action: "wp_video_gallery_ajax_duplicate_grid",
				id: that.data('id')
			}
		;
		e.preventDefault();
		e.stopPropagation();
		if (that.hasClass('disabled'))
			return false;
		that.html("<i class='fa fa-spin fa-spinner'></i>").addClass('disabled');
		$.ajax({
            type: "POST",
            data: postdata,
            url: ajaxurl,
            success: function(data){
                window.location.reload();
            }
        });
	})

})(window.jQuery);
