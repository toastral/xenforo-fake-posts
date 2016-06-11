<?php
class Post extends DB{

  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `thread_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `post_date` int(10) unsigned NOT NULL,
  `message` mediumtext NOT NULL,
  `ip_id` int(10) unsigned NOT NULL DEFAULT '0',
  `message_state` enum('visible','moderated','deleted') NOT NULL DEFAULT 'visible',
  `attach_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `position` int(10) unsigned NOT NULL,
  `likes` int(10) unsigned NOT NULL DEFAULT '0',
  `like_users` blob NOT NULL,
  `warning_id` int(10) unsigned NOT NULL DEFAULT '0',
  `warning_message` varchar(255) NOT NULL DEFAULT '',
  `last_edit_date` int(10) unsigned NOT NULL DEFAULT '0',
  `last_edit_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `edit_count` int(10) unsigned NOT NULL DEFAULT '0',

    public $table = 'xf_post';

    function __construct(){
        parent::__construct();
    }
    
}
?>