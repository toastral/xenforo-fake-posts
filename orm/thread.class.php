<?php
class Thread extends DB{
(
  `thread_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `node_id` int(10) unsigned NOT NULL,
  `title` varchar(150) NOT NULL,
  `reply_count` int(10) unsigned NOT NULL DEFAULT '0',
  `view_count` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `post_date` int(10) unsigned NOT NULL,
  `sticky` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `discussion_state` enum('visible','moderated','deleted') NOT NULL DEFAULT 'visible',
  `discussion_open` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `discussion_type` varchar(25) NOT NULL DEFAULT '',
  `first_post_id` int(10) unsigned NOT NULL,
  `first_post_likes` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post_date` int(10) unsigned NOT NULL,
  `last_post_id` int(10) unsigned NOT NULL,
  `last_post_user_id` int(10) unsigned NOT NULL,
  `last_post_username` varchar(50) NOT NULL,
  `prefix_id` int(10) unsigned NOT NULL DEFAULT '0',
  `tags` mediumblob NOT NULL,

    public $table = 'xf_thread';

    function __construct(){
        parent::__construct();
    }
    
}



?>