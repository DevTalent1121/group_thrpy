(function($) {
	var protocol = "http";
	if (document.location.protocol == "https:" || window.location.protocol == "https:") {
		protocol = "https";
	}
	$(window).on('load', function() {
		$(".createNewGrid").on('click', function() {
			var that   = $(this),
				parent = that.closest('.add-grid-back'),
				title  = parent.find('.titleInput').val(),
				error  = parent.find('.error');
			if (!that.hasClass('disabled')) {
				error.hide();
				if (title == "") {
					error.fadeIn(400);
				}
				else {
					that.html('<i class="fa fa-spin fa-spinner"></i>').addClass('disabled');
					that.find('.titleInput').attr('disabled', true);
					$.ajax({
			            type: "POST",
			            data: {
			            	action : "wp_video_gallery_ajax_add_grid",
			            	title  : title
			            },
			            url: ajaxurl,
			            success: function(data){
			                window.location.href = location.origin + location.pathname + "?page=wp_video_gallery-edit-grids&id=" + data;
		                }
		            });
				}
			}
		})

		$(document).on('keypress', '#vimeoMajbaGrids .titleInput', function(event) {
			 if (event.keyCode == 13) {
			 	$('.createNewGrid').trigger("click");
			 }
		})
		$(document).on('keypress', '.vimeoAT', function(event) {
			 if (event.keyCode == 13) {
			 	$('.saveVimeoConf').trigger("click");
			 }
		})
		$(document).on('keypress', '.youtubeAT', function(event) {
			 if (event.keyCode == 13) {
			 	$('.saveYoutubeConf').trigger("click");
			 }
		})

		$(document).on("click", '.saveVimeoConf', function() {
			var that    = $(this),
				html    = that.html(),
				vimeoT  = $('.vimeoAT').val();
			if (that.hasClass('disabled'))
				return false;
			that.addClass('disabled');
			$('#wp_video_gallery-account .vimeoPrivateBox p.error').hide();
			that.html('<i class="fa fa-spinner fa-spin"></i>');
			$.ajax({
	            type: "POST",
	            data: {
	            	action  : "wp_video_gallery_ajax_test_vimeo_account",
	            	vimeoT  : vimeoT
	            },
	            url: ajaxurl,
	            success: function(data) {
	                if (data == '0') {
	                	$('#wp_video_gallery-account .vimeoPrivateBox p.error').fadeIn();
	                	that.html(html);
	                	that.removeClass('disabled');
	                }
	                else {
	                	that.html("<i class='fa fa-check'></i>")
	                	window.location.href = data;
	                }
	            }
	        });			
		})
		$(document).on("click", '.saveYoutubeConf', function() {
			var that     = $(this),
				html     = that.html(),
				youtubeT = $('.youtubeAT').val();
			if (that.hasClass('disabled'))
				return false;
			that.addClass('disabled');
			$('#wp_video_gallery-account .youtubePrivateBox p.error').hide();
			that.html('<i class="fa fa-spinner fa-spin"></i>');
			$.ajax({
	            type: "POST",
	            data: {
	            	action  : "wp_video_gallery_ajax_test_youtube_api_key",
	            	youtubeT  : youtubeT
	            },
	            url: ajaxurl,
	            success: function(data) {
	                if (data == '0') {
	                	$('#wp_video_gallery-account .youtubePrivateBox p.error').fadeIn();
	                	that.html(html);
	                	that.removeClass('disabled');
	                }
	                else {
	                	that.html("<i class='fa fa-check'></i>")
	                	window.location.href = data;
	                }
	            }
	        });			
		})

		if ($('.deleteVimeoConf').length) {
			$('.deleteVimeoConf').magnificPopup({
		  		items: {
		      		src: '#account-popup',
	      			type: 'inline'
			  	}
			});
		}
		if ($('.deleteYoutubeConf').length) {
			$('.deleteYoutubeConf').magnificPopup({
		  		items: {
		      		src: '#account-popup-Y',
	      			type: 'inline'
			  	}
			});
		}

		$(document).on('click', '#account-popup .cancel, #account-popup-Y .cancel', function() {
			$.magnificPopup.close();
		})

		$(document).on('click', '#account-popup .clear', function() {
			var that  = $(this),
				modal = that.closest('#account-popup');

			modal.html('<i class="fa fa-spin fa-spinner"></i>')
			$.ajax({
				type: "POST",
				data: {
					action: "wp_video_gallery_ajax_disconnect_clear"
				},
				url: ajaxurl,
				success: function(data) {
					window.location.href = data;
				}
			})
		})

		$(document).on('click', '#account-popup-Y .clear', function() {
			var that  = $(this),
				modal = that.closest('#account-popup-Y');

			modal.html('<i class="fa fa-spin fa-spinner"></i>')
			$.ajax({
				type: "POST",
				data: {
					action: "wp_video_gallery_ajax_disconnect_clear_y"
				},
				url: ajaxurl,
				success: function(data) {
					window.location.href = data;
				}
			})
		})

		$(document).on('click', '#account-popup .keep', function() {
			var that  = $(this),
				modal = that.closest('#account-popup');

			modal.html('<i class="fa fa-spin fa-spinner"></i>')
			$.ajax({
				type: "POST",
				data: {
					action: "wp_video_gallery_ajax_disconnect_keep"
				},
				url: ajaxurl,
				success: function(data) {
					window.location.href = data;
				}
			})
		})

		$(document).on('click', '#account-popup-Y .keep', function() {
			var that  = $(this),
				modal = that.closest('#account-popup-Y');

			modal.html('<i class="fa fa-spin fa-spinner"></i>')
			$.ajax({
				type: "POST",
				data: {
					action: "wp_video_gallery_ajax_disconnect_keep_y"
				},
				url: ajaxurl,
				success: function(data) {
					window.location.href = data;
				}
			})
		})

		var refresh_vimeo = function() {
			var videos_count_db = 0;
			var videos_count_query = 0;
			$.ajax({
	            type: "POST",
	            data: {
	            	action  : "wp_video_gallery_ajax_get_nbr_videos_db"
	            },
	            url: ajaxurl,
	            success: function(data){
	                videos_count_db = data;
	                $.ajax({
			            type: "POST",
			            data: {
			            	action  : "wp_video_gallery_ajax_get_nbr_videos_vimeo"
			            },
			            url: ajaxurl,
			            success: function(data){
			                videos_count_query = data;
			                start_refreshing_vimeo(videos_count_query, videos_count_db);
			            }
			        });
	            }
	        });	
		}

		var start_refreshing_vimeo = function(videos_count_query, videos_count_db) {
			var percent = (videos_count_db / videos_count_query) * 100;
			$('.refresh_modal .modal-content .progBar div').css('width', percent + "%");
			$('.refresh_modal .modal-content .progBar p').html(percent + "%");
			$('.refresh_modal .modal-content .done').html(videos_count_db);
			$('.refresh_modal .modal-content .todo').html(videos_count_query);
			$('.refresh_modal').fadeIn(400);
			var interRef = setInterval(function() {
				$.ajax({
		            type: "POST",
		            data: {
		            	action  : "wp_video_gallery_ajax_get_nbr_videos_db"
		            },
		            url: ajaxurl,
		            success: function(data){
		            	videos_count_db = data;
		            	percent = (videos_count_db / videos_count_query) * 100;
						$('.refresh_modal .modal-content .progBar div').css('width', percent + "%");
		                $('.refresh_modal .modal-content .done').html(videos_count_db);
		            }
		        });	
			}, 1000)
			$.ajax({
	            type: "POST",
	            data: {
	            	action    : "wp_video_galleryAjaxRefreshVideos"
	            },
	            url: ajaxurl,
	            success: function(data) {
	            	clearInterval(interRef);
	            	$('.refresh_modal').fadeOut(300);
	            }
	        });
		}

		$(document).on("click", '.enableVimeo', function(e) {
			var that = $(this);
			e.preventDefault();
			$.ajax({
	            type: "POST",
	            data: {
	            	action  : "wp_video_gallery_ajax_enable_disable_vimeo",
	            	mode    : 1
	            },
	            url: ajaxurl,
	            success: function(data){
					$('#wp_video_gallery-account table.vimeo .disabled').fadeOut(400, function() {
	                	$(this).remove();
	                })
	                that.html(data).removeClass('enableVimeo').addClass('disableVimeo');
	            }
	        });
		})

		$(document).on('click', '.public .vimeoPublicBox .addVimeoVideo', function() {
			var that = $(this),
				url  = $('.public .vimeoPublicBox input').val();
			if (that.hasClass('disabled'))
				return false;
			$('.public .vimeoPublicBox .error, .public .vimeoPublicBox .duplicate').hide();
			if (url == '') {
				$('.public .vimeoPublicBox .error').show();
				$('.public .vimeoPublicBox input').focus();
				return false;
			}
			that.addClass('disabled');
			that.removeClass('fa-plus-circle').addClass('fa-spin').addClass('fa-spinner').css('color', 'black');
			$('.public .vimeoPublicBox input').attr('disabled', true);
			$.ajax({
	            type: "POST",
	            data: {
	            	action  : "wp_video_gallery_ajax_add_single_vimeo",
	            	url     : url
	            },
	            url: ajaxurl,
	            success: function(data) {
	            	that.removeClass('disabled');
	            	that.addClass('fa-plus-circle').removeClass('fa-spin').removeClass('fa-spinner').css('color', 'green');
	            	$('.public .vimeoPublicBox input').attr('disabled', false);
	            	if (data == "error") {
						$('.public .vimeoPublicBox .error').show();
						$('.public .vimeoPublicBox input').focus();
	            	}
	            	else if (data == "duplicate") {
	            		$('.public .vimeoPublicBox .duplicate').show();
						$('.public .vimeoPublicBox input').focus();	
	            	}
	            	else {
	            		data = data.split('####');
	            		$('.public .registredVideos').html(data[0]);
	            		$('.vimeo-nav .over-tab-count').html(data[1]);
	            		$('.public .vimeoPublicBox input').val('');
	            		$('.public .registredVideos .around').scrollTop($('.public .registredVideos .around').height());
	            	}
	            	$('.public .registredVideos .line').each(function() {
						var id    = $(this).data('id');
						$(this).find('.title, .thumb').magnificPopup({
						    items: {
						      src: protocol + '://vimeo.com/' + id
						    },
						    type: 'iframe'
						});
					})
	            }
	        });
		})

		$(document).on('keypress', '.public .vimeoPublicBox input', function(event) {
			if (event.keyCode == 13) {
			 	$('.public .vimeoPublicBox .addVimeoVideo').trigger("click");
			 }
		})

		$(document).on('click', '.youtube .vimeoPublicBox .addYoutubeVideo', function() {
			var that = $(this),
				url  = $('.youtube .vimeoPublicBox.youtube-single input').val();
			if (that.hasClass('disabled'))
				return false;
			$('.youtube .vimeoPublicBox.youtube-single .error, .youtube .vimeoPublicBox.youtube-single .duplicate').hide();
			if (url == '') {
				$('.youtube .vimeoPublicBox.youtube-single .error').show();
				$('.youtube .vimeoPublicBox.youtube-single input').focus();
				return false;
			}
			that.addClass('disabled');
			that.removeClass('fa-plus-circle').addClass('fa-spin').addClass('fa-spinner').css('color', 'black');
			$('.youtube .vimeoPublicBox.youtube-single input').attr('disabled', true);
			$.ajax({
	            type: "POST",
	            data: {
	            	action  : "wp_video_gallery_ajax_add_single_youtube",
	            	url     : url
	            },
	            url: ajaxurl,
	            success: function(data) {
	            	that.removeClass('disabled');
	            	that.addClass('fa-plus-circle').removeClass('fa-spin').removeClass('fa-spinner').css('color', 'green');
	            	$('.youtube .vimeoPublicBox.youtube-single input').attr('disabled', false);
	            	if (data == "error") {
						$('.youtube .vimeoPublicBox.youtube-single .error').show();
						$('.youtube .vimeoPublicBox.youtube-single input').focus();
	            	}
	            	else if (data == "duplicate") {
	            		$('.youtube .vimeoPublicBox.youtube-single .duplicate').show();
						$('.youtube .vimeoPublicBox.youtube-single input').focus();	
	            	}
	            	else {
	            		data = data.split('####');
	            		$('.youtube .registredVideos').html(data[0]);
	            		$('.youtube-nav .over-tab-count').html(data[1]);
	            		$('.youtube .vimeoPublicBox.youtube-single input').val('');
	            		$('.youtube .registredVideos .around').scrollTop($('.youtube .registredVideos .around').height());
	            	}
	            	$('.youtube .registredVideos .line').each(function() {
						var id    = $(this).data('id');
						$(this).find('.title, .thumb').magnificPopup({
						    items: {
						      src: protocol + '://www.youtube.com/watch?v=' + id
						    },
						    type: 'iframe'
						});
					})
	            }
	        });
		})

		$(document).on('click', '.youtube .vimeoPublicBox .addYoutubeChannel', function() {
			var that = $(this),
				url  = $('.youtube .vimeoPublicBox.youtube-channel input').val();
			if (that.hasClass('disabled'))
				return false;
			$('.youtube .vimeoPublicBox.youtube-channel .error, .youtube .vimeoPublicBox.youtube-channel .added-videos').hide();
			if (url == '') {
				$('.youtube .vimeoPublicBox.youtube-channel .error').show();
				$('.youtube .vimeoPublicBox.youtube-channel input').focus();
				return false;
			}
			that.addClass('disabled');
			that.removeClass('fa-plus-circle').addClass('fa-spin').addClass('fa-spinner').css('color', 'black');
			$('.youtube .vimeoPublicBox.youtube-channel input').attr('disabled', true);
			$.ajax({
	            type: "POST",
	            data: {
	            	action  : "wp_video_gallery_ajax_add_channel_youtube",
	            	url     : url
	            },
	            url: ajaxurl,
	            success: function(data) {
	            	that.removeClass('disabled');
	            	that.addClass('fa-plus-circle').removeClass('fa-spin').removeClass('fa-spinner').css('color', 'green');
	            	$('.youtube .vimeoPublicBox.youtube-channel input').attr('disabled', false);
	            	if (data == "error") {
						$('.youtube .vimeoPublicBox.youtube-channel .error').show();
						$('.youtube .vimeoPublicBox.youtube-channel input').focus();
	            	}
	            	else {
	            		data = data.split('####');
	            		$('.youtube .registredVideos').html(data[0]);
	            		$('.youtube .vimeoPublicBox.youtube-channel input').val('');
	            		$('.youtube .registredVideos .around').scrollTop($('.youtube .registredVideos .around').height());
	            		$('.youtube .vimeoPublicBox.youtube-channel .added-videos b').html(data[1]);
	            		$('.youtube .vimeoPublicBox.youtube-channel .added-videos').show();
	            		$('.youtube-nav .over-tab-count').html(data[2]);
	            	}
	            	$('.youtube .registredVideos .line').each(function() {
						var id    = $(this).data('id');
						$(this).find('.title, .thumb').magnificPopup({
						    items: {
						      src: protocol + '://www.youtube.com/watch?v=' + id
						    },
						    type: 'iframe'
						});
					})
	            }
	        });
		})

		$('.public .registredVideos .line').each(function() {
			var id    = $(this).data('id');
			$(this).find('.title, .thumb').magnificPopup({
			    items: {
			      src: protocol + '://vimeo.com/' + id
			    },
			    type: 'iframe'
			});
		})

		$('.youtube .registredVideos .line').each(function() {
			var id    = $(this).data('id');
			$(this).find('.title, .thumb').magnificPopup({
			    items: {
			      src: protocol + '://www.youtube.com/watch?v=' + id
			    },
			    type: 'iframe'
			});
		})

		$(document).on('keypress', '.youtube .vimeoPublicBox input', function(event) {
			if (event.keyCode == 13) {
				$(this).closest('.vimeoPublicBox').find('.fa-plus-circle').trigger("click");
			 }
		})

		$(document).on('click', '.registredVideos .removeSingleVideo', function() {
			var uri = $(this).data('uri');
			$.ajax({
	            type: "POST",
	            data: {
	            	action  : "wp_video_gallery_ajax_remove_single_vimeo",
	            	uri     : uri
	            },
	            url: ajaxurl,
	            success: function(data) {
	            	data = data.split("####");
	            	$('.public .registredVideos').html(data[0]);
	            	$('.vimeo-nav .over-tab-count').html(data[1]);
	            	$('.public .registredVideos .line').each(function() {
						var id    = $(this).data('id');
						$(this).find('.title, .thumb').magnificPopup({
						    items: {
						      src: protocol + '://vimeo.com/' + id
						    },
						    type: 'iframe'
						});
					})
	            }
	        });
		})

		$(document).on('click', '.registredVideos .removeYoutubeVideo', function() {
			var uri = $(this).data('uri');
			$.ajax({
	            type: "POST",
	            data: {
	            	action  : "wp_video_gallery_ajax_remove_single_youtube",
	            	uri     : uri
	            },
	            url: ajaxurl,
	            success: function(data) {
	            	data = data.split("####");
	            	$('.youtube .registredVideos').html(data[0]);
	            	$('.youtube-nav .over-tab-count').html(data[1]);
	            	$('.youtube .registredVideos .line').each(function() {
						var id    = $(this).data('id');
						$(this).find('.title, .thumb').magnificPopup({
						    items: {
						      src: protocol + '://www.youtube.com/watch?v=' + id
						    },
						    type: 'iframe'
						});
					})
	            }
	        });
		})

		$(document).on("click", '.disableVimeo', function(e) {
			var that = $(this);
			e.preventDefault();
			$.ajax({
	            type: "POST",
	            data: {
	            	action  : "wp_video_gallery_ajax_enable_disable_vimeo",
	            	mode    : 0
	            },
	            url: ajaxurl,
	            success: function(data){
	            	$('#wp_video_gallery-account table.vimeo').prepend('<tr class="disabled" style="display:none;"></tr>')
	                $('#wp_video_gallery-account table.vimeo .disabled').fadeIn(400);
	                that.html(data).removeClass('disableVimeo').addClass('enableVimeo');
	            }
	        });
		})

		$(document).on("click", '#wp_video_gallery-account .private .refresh_base', function(e) {
			e.preventDefault();
			var that = $(this);
			if (!that.hasClass("disabled")) {
				that.find('.fa').addClass('fa-spin');
				that.addClass("disabled");
				$.ajax({
		            type: "POST",
		            data: {
		            	action  : "wp_video_galleryForceVimeoPrivateVideosRefresh"
		            },
		            url: ajaxurl,
		            success: function(data){
		            	$('#wp_video_gallery-account').html(data);
		            	initCatalogPage();
		            }
		        });
			}
		})

		$(document).on('click', '.catalog .navigation .vimeo-nav', function() {
			$(this).addClass('active');
			$('.catalog .navigation .youtube-nav').removeClass('active');
			$('.catalog .sections .youtube-section').stop().hide();
			$('.catalog .sections .vimeo-section').stop().show();
		})

		$(document).on('click', '.catalog .navigation .youtube-nav', function() {
			$(this).addClass('active');
			$('.catalog .navigation .vimeo-nav').removeClass('active');
			$('.catalog .sections .vimeo-section').stop().hide();
			$('.catalog .sections .youtube-section').stop().show();
		})

	})

	var registredHeight = function() {
		var bh = $(window).height(),
			ao = $('.around').offset().top;
		var h = bh - ao - 65;
		$('.around').height(h);
	}

	if ($('.loadingCatalog').length) {
		$.ajax({
            type: "POST",
            data: {
            	action    : "wp_video_galleryAjaxRefreshVideosCatalog"
            },
            url: ajaxurl,
            success: function(data){
            	$('.loadingCatalog').remove();
                $('#wp_video_gallery-account').html(data).fadeIn(700);
                initCatalogPage()
            }
        });

		$(window).on('resize', function() {
			set_account_iframes_dims();
		})
	}

	var initCatalogPage = function() {
		registredHeight();
        $(window).scrollTop($(window).scrollTop()+1);
		$(window).scrollTop($(window).scrollTop()-1);
        $('.registredVideos .around').bind('scroll', function() {
			$(window).scrollTop($(window).scrollTop()+1);
			$(window).scrollTop($(window).scrollTop()-1);
		})
		$('.public .registredVideos .line').each(function() {
			var id    = $(this).data('id');
			$(this).find('.title, .thumb').magnificPopup({
			    items: {
			      src: protocol + '://vimeo.com/' + id
			    },
			    type: 'iframe'
			});
		})
		$('.private .registredVideos .line').each(function() {
			var id    = $(this).data('id');
			$(this).find('.title, .thumb').magnificPopup({
			    items: {
			      src: protocol + '://vimeo.com/' + id
			    },
			    type: 'iframe'
			});
		})
		$('.youtube .registredVideos .line').each(function() {
			var id    = $(this).data('id');
			$(this).find('.title, .thumb').magnificPopup({
			    items: {
			      src: protocol + '://www.youtube.com/watch?v=' + id
			    },
			    type: 'iframe'
			});
		})
		$('.deleteVimeoConf').magnificPopup({
	  		items: {
	      		src: '#account-popup',
      			type: 'inline'
		  	}
		});
		$('.deleteYoutubeConf').magnificPopup({
	  		items: {
	      		src: '#account-popup-Y',
      			type: 'inline'
		  	}
		});
	}

	var set_account_iframes_dims = function() {
		if ($('#wp_video_gallery-account iframe').length) {
			$('#wp_video_gallery-account iframe').each(function() {
				var w = $(this).width();
				$(this).height(w * 394 / 700);
			})
		}
	}

	$(document).on('load ready', function() {
		set_account_iframes_dims();
	})

	$(window).on('resize', function() {
			set_account_iframes_dims();
		})

	$(document).on('keypress', '.wp_video_gallery-pro-key-input', function(event) {
		 if (event.keyCode == 13) {
		 	$('.validate-pro-key').trigger("click");
		 }
	})

	$(document).on('click', '.validate-pro-key', function() {
		var that = $(this),
			val = $('.wp_video_gallery-pro-key-input').val();
		if (that.hasClass('disabled'))
			return false;
		that.addClass('disabled');
		$('.wp_video_gallery-pro-key-input').attr('disabled', true)
		$('.activation_errors .error').hide();
		$.ajax({
            type: "POST",
            data: {
            	action : "wp_video_galleryValidateProKey",
            	value  : val
            },
            url: ajaxurl,
            success: function(data) {
            	if (data == 1)
            		window.location.reload()
            	else {
            		$('.activation_errors .error-' + data).fadeIn();
            		that.removeClass('disabled');
            		$('.wp_video_gallery-pro-key-input').attr('disabled', false)
            	}
            }
        });
	})

	$(document).on('click', '.import-demo-button', function() {
		var that = $(this);
		if (that.hasClass('disabled'))
			return false;
		that.addClass('disabled').attr('disabled', true).html("<i class='fa fa-spin fa-spinner'></i>");
		$.ajax({
            type: "POST",
            data: {
            	action : "wp_video_galleryImportDemo"
            },
            url: ajaxurl,
            success: function(data) {
            	that.html("<i class='fa fa-check'></i>")
            	window.location.href = data;
            }
        });	
	})


	$(document).on('click', '.private .registredVideos .refreshVimeoVideo', function() {
		var that = $(this),
			uri = that.data('uri'),
			id = that.closest('.line').data('id');
		that.addClass('fa-spin');
		$.ajax({
            type: "POST",
            dataType: "json",
            data: {
            	action  : "wp_video_gallery_ajax_refresh_single_vimeo",
            	uri     : uri
            },
            url: ajaxurl,
            success: function(data) {
            	that.removeClass("fa-spin");
            	if (data.status == 1) {
            		that.closest('.line').replaceWith(data.html);
            		$('.line[data-id="' + id + '"]').find('.title, .thumb').magnificPopup({
					    items: {
					      src: protocol + '://vimeo.com/' + id
					    },
					    type: 'iframe'
					});
            	}
            }
        });
	})

	$(document).on('click', '.public .registredVideos .refreshVimeoVideo', function() {
		var that = $(this),
			uri = that.data('uri'),
			id = that.closest('.line').data('id');
		that.addClass('fa-spin');
		$.ajax({
            type: "POST",
            dataType: "json",
            data: {
            	action  : "wp_video_gallery_ajax_refresh_single_vimeo_public",
            	uri     : uri
            },
            url: ajaxurl,
            success: function(data) {
            	that.removeClass("fa-spin");
            	if (data.status == 1) {
            		that.closest('.line').replaceWith(data.html);
            		$('.line[data-id="' + id + '"]').find('.title, .thumb').magnificPopup({
					    items: {
					      src: protocol + '://vimeo.com/' + id
					    },
					    type: 'iframe'
					});
            	}
            }
        });
	})

	$(document).on('click', '.youtube .registredVideos .refresh_base', function() {
		var that = $(this);
		if (that.find('i').hasClass('fa-spin'))
			return false;
		that.find('i').addClass('fa-spin');
		$.ajax({
            type: "POST",
            dataType: "json",
            data: {
            	action  : "wp_video_galleryForceYoutubeVideosRefresh"
            },
            url: ajaxurl,
            success: function(data) {
            	that.find('i').removeClass("fa-spin");
            	console.log(data)
        		that.closest('.line').replaceWith(data.html);
        		$('.youtube .registredVideos .around table').html(data.lines);
        	}
        });
	})


})(window.jQuery);