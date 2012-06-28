<?php 
    $route['tags/rss(/.*)'] = "tags/rss$1";
	$route['tags(admin/:any)']  = "tags/admin$1";
	$route['tags([^admin].*)']      = "tags/index$1"; 
?>