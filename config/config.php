<?php 
	function base_url($path = '') {
		echo "/si_inventaris/" . $path;
	}

	function base_url_return($path = '') {
		return "/si_inventaris/" . $path;
	}

    date_default_timezone_set("Asia/Bangkok");
	
	DEFINE("SITE_NAME", "PT Pinus Merah Abadi");
	DEFINE("SITE_NAME_SHORT", "PMA");
?>