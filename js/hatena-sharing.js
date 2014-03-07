jQuery(document).ready(function(){
	if ('undefined' != typeof WPCOM_sharing_counts) {
		for (var url in WPCOM_sharing_counts) {
			get_hatena_counts(url);
		}
	}
	function get_hatena_counts(url) {
		$.ajax({
			url     : "http://api.b.st-hatena.com/entry.count",
			data    : {url: encodeURI(url)},
			dataType: "jsonp",
			success : update_hatena_count
		});
	}

	function update_hatena_count(data) {
		if ('undefined' != data && data > 0) {
			WPCOMSharing.inject_share_count('sharing-hatena-' + WPCOM_sharing_counts[ url ], data);
		}
	}

});
