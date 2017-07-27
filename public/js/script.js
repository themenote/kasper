jQuery(document).on("click", "#lb-loadmore", function() {
	var _self = jQuery(this),
		_postlistWrap = jQuery('.blog-posts'),
		_button = jQuery('#lb-loadmore'),
		_data = _self.data();
	if (_self.hasClass('is-loading')) {
		return false
	} else {
		_button.html('<div class="csshub-loader"><h1></h1><span></span><span></span><span></span></div>');
		_self.addClass('is-loading');
		jQuery.ajax({
			url: barley.ajaxurl,
			data: _data,
			type: 'post',
			dataType: 'json',
			success: function(data) {
				if (data.code == 500) {
					_button.data("paged", data.next).html('加载更多');
					alert('服务器正在努力找回自我  o(∩_∩)o')
				} else if (data.code == 200) {
					_postlistWrap.append(data.postlist);
					if (data.next) {
						_button.data("paged", data.next).html('加载更多')
					} else {
						_button.remove()
					}
				}
				_self.removeClass('is-loading')
			}
		})
	}
});