/**
 * @author http://www.cosmosfarm.com/
 */

/**
 * inViewport jQuery plugin by Roko C.B.
 * http://stackoverflow.com/a/26831113/383904 Returns a callback function with
 * an argument holding the current amount of px an element is visible in
 * viewport (The min returned value is 0 (element outside of viewport)
 */
(function($, win){
	$.fn.kboardViewport = function(cb){
		return this.each(function(i, el){
			function visPx(){
				var elH = $(el).outerHeight(), H = $(win).height(), r = el.getBoundingClientRect(), t = r.top, b = r.bottom;
				return cb.call(el, Math.max(0, t > 0 ? Math.min(elH, H - t) : (b < H ? b : H)));
			}
			visPx();
			$(win).on("resize scroll", visPx);
		});
	};
}(jQuery, window));

var kboard_ajax_lock = false;

function kboard_editor_open_media(){
	var w = 900;
	var h = 500;
	
	if(kbaord_current.board_id){
		if(jQuery('#kboard_media_wrapper').length){
			jQuery('#kboard_media_wrapper').show();
			jQuery('#kboard_media_wrapper').html(jQuery('<iframe frameborder="0"></iframe>').attr('src', kboard_settings.home_url+'?action=kboard_media&board_id='+kbaord_current.board_id+'&media_group='+kboard_settings.media_group+'&content_uid='+kbaord_current.content_uid));
			jQuery('#kboard_media_background').show();
		}
		else{
			var wrapper = jQuery('<div id="kboard_media_wrapper"></div>');
			var background = jQuery('<div id="kboard_media_background"></div>').css({opacity:'0.5'}).click(function(){
				kboard_media_close();
			});
			
			function init_window_size(){
				if(window.innerWidth <= 900){
					wrapper.css({left:0, top:0, margin:'10px', width:(window.innerWidth-20), height:(window.innerHeight-20)});
				}
				else{
					wrapper.css({left:'50%', top:'50%', margin:0, 'margin-left':(w/2)*-1, 'margin-top':(h/2)*-1, width:w, height:h});
				}
			}
			init_window_size();
			jQuery(window).resize(init_window_size);
			
			wrapper.html(jQuery('<iframe frameborder="0"></iframe>').attr('src', kboard_settings.home_url+'?action=kboard_media&board_id='+kbaord_current.board_id+'&media_group='+kboard_settings.media_group+'&content_uid='+kbaord_current.content_uid));
			jQuery('body').append(background);
			jQuery('body').append(wrapper);
			
			if(!jQuery('input[name="media_group"]').filter(function(){return this.value==kboard_settings.media_group}).length){
				jQuery('[name="board_id"]').parents('form').append(jQuery('<input type="hidden" name="media_group">').val(kboard_settings.media_group));
			}
		}
	}
}

function kboard_editor_insert_media(url){
	if(typeof tinyMCE != 'undefined'){
		if(typeof tinyMCE.activeEditor != 'undefined'){
			tinyMCE.activeEditor.execCommand('mceInsertRawHTML', false, "<img id=\"last_kboard_media_content\" src=\""+url+"\" alt=\"\">");
			tinyMCE.activeEditor.focus();
			tinyMCE.activeEditor.selection.select(tinyMCE.activeEditor.dom.select('#last_kboard_media_content')[0], true);
			tinyMCE.activeEditor.selection.collapse(false);
			tinyMCE.activeEditor.dom.setAttrib('last_kboard_media_content', 'id', '');
		}
	}
	else if(jQuery('#kboard_content').length){
		jQuery('#kboard_content').val(function(index, value){
			return value + (!value?'':' ') + "<img src=\""+url+"\" alt=\"\">";
		});
	}
}

function kboard_media_close(){
	jQuery('#kboard_media_wrapper').hide();
	jQuery('#kboard_media_background').hide();
}

function kboard_document_print(url){
	window.open(url, 'kboard_document_print');
	return false;
}

function kboard_document_like(button, callback){
	if(!kboard_ajax_lock){
		kboard_ajax_lock = true;
		jQuery.post(kboard_settings.alax_url, {'action':'kboard_document_like', 'document_uid':jQuery(button).data('uid'), 'security':kboard_settings.ajax_security}, function(res){
			kboard_ajax_lock = false;
			if(typeof callback === 'function'){
				callback(res);
			}
			else{
				if(res.result == 'error'){
					alert(res.message);
				}
				else{
					jQuery('.kboard-document-like-count', button).text(res.data.like);
				}
			}
		});
	}
	else{
		alert(kboard_localize_strings.please_wait);
	}
	return false;
}

function kboard_document_unlike(button, callback){
	if(!kboard_ajax_lock){
		kboard_ajax_lock = true;
		jQuery.post(kboard_settings.alax_url, {'action':'kboard_document_unlike', 'document_uid':jQuery(button).data('uid'), 'security':kboard_settings.ajax_security}, function(res){
			kboard_ajax_lock = false;
			if(typeof callback === 'function'){
				callback(res);
			}
			else{
				if(res.result == 'error'){
					alert(res.message);
				}
				else{
					jQuery('.kboard-document-unlike-count', button).text(res.data.unlike);
				}
			}
		});
	}
	else{
		alert(kboard_localize_strings.please_wait);
	}
	return false;
}

function kboard_comment_like(button, callback){
	if(!kboard_ajax_lock){
		kboard_ajax_lock = true;
		jQuery.post(kboard_settings.alax_url, {'action':'kboard_comment_like', 'comment_uid':jQuery(button).data('uid'), 'security':kboard_settings.ajax_security}, function(res){
			kboard_ajax_lock = false;
			if(typeof callback === 'function'){
				callback(res);
			}
			else{
				if(res.result == 'error'){
					alert(res.message);
				}
				else{
					jQuery('.kboard-comment-like-count', button).text(res.data.like);
				}
			}
		});
	}
	else{
		alert(kboard_localize_strings.please_wait);
	}
	return false;
}

function kboard_comment_unlike(button, callback){
	if(!kboard_ajax_lock){
		kboard_ajax_lock = true;
		jQuery.post(kboard_settings.alax_url, {'action':'kboard_comment_unlike', 'comment_uid':jQuery(button).data('uid'), 'security':kboard_settings.ajax_security}, function(res){
			kboard_ajax_lock = false;
			if(typeof callback === 'function'){
				callback(res);
			}
			else{
				if(res.result == 'error'){
					alert(res.message);
				}
				else{
					jQuery('.kboard-comment-unlike-count', button).text(res.data.unlike);
				}
			}
		});
	}
	else{
		alert(kboard_localize_strings.please_wait);
	}
	return false;
}

function kboard_content_update(content_uid, data, callback){
	if(!kboard_ajax_lock){
		kboard_ajax_lock = true;
		jQuery.post(kboard_settings.alax_url, {'action':'kboard_content_update', 'content_uid':content_uid, 'data':data, 'security':kboard_settings.ajax_security}, function(res){
			kboard_ajax_lock = false;
			if(typeof callback === 'function'){
				callback(res);
			}
		});
	}
	else{
		alert(kboard_localize_strings.please_wait);
	}
	return false;
}

function kboard_ajax_builder(args, callback){
	if(!kboard_ajax_lock){
		kboard_ajax_lock = true;
		var callback2 = (typeof callback === 'function') ? callback : args['callback'];
		args['action'] = 'kboard_ajax_builder';
		args['callback'] = '';
		args['security'] = kboard_settings.ajax_security;
		jQuery.get(kboard_settings.alax_url, args, function(res){
			kboard_ajax_lock = false;
			if(typeof callback2 === 'function'){
				callback2(res);
			}
		});
	}
	else{
		alert(kboard_localize_strings.please_wait);
	}
	return false;
}