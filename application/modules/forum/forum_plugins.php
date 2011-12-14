<?php 


/*
by default, we use gravatar.
To overwrite the avatar image, you can add your own plugin like this

// $user_aray is an array with 2 keys : username, email

$this->add_action('forum_avatar_image', 'my_forum_avatar_image', 10);

function my_forum_avatar_image($user_array)
{
	//first remove the default action
	$obj =& get_instance();
	$obj->plugin->remove_action('forum_avatar_image', 'forum_forum_avatar_image', 20);

	echo "<img src='http://avatar.serasera.org/" .  md5($user_array['username']) . ".jpg' align='left' hspace='5' width='40' height='40' class='avatar'/>";
}

*/

$this->add_action('forum_avatar_image', 'forum_forum_avatar_image', 20);

function forum_forum_avatar_image($user_array)
{
	//using gravatar
		echo "<img src='http://www.gravatar.com/avatar/" . md5(strtolower($user_array['email'])) . ".jpg' align='left' hspace='5' width='40' height='40' class='avatar'/>";
	
}
