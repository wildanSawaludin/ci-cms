<?php 
	$route['news/rss(.*)'] = "news/rss$1";
	$route['news/list(/:any)?'] = "news/index$1";
	$route['news/tag(.*)'] = "news/tag$1";
	$route['news/cat(.*)'] = "news/cat$1";
	$route['news/comment(.*)'] = "news/comment$1";
	$route['news^(/admin.*)'] = "news/admin$1";
	$route['news(/.*)'] = "news/read$1"; 
?>