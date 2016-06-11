<?php
class Forum extends DB{
    
CREATE TABLE `xf_forum` (
  `node_id` int(10) unsigned NOT NULL,
  `discussion_count` int(10) unsigned NOT NULL DEFAULT '0',
  `message_count` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Most recent post_id',
  `last_post_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Date of most recent post',
  `last_post_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'User_id of user posting most recently',
  `last_post_username` varchar(50) NOT NULL DEFAULT '' COMMENT 'Username of most recently-posting user',
  `last_thread_title` varchar(150) NOT NULL DEFAULT '' COMMENT 'Title of thread most recent post is in',
  `moderate_threads` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `moderate_replies` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `allow_posting` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `allow_poll` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `count_messages` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'If not set, messages posted (directly) within this forum will not contribute to user message totals.',
  `find_new` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Include posts from this forum when running /find-new/threads',
  `prefix_cache` mediumblob NOT NULL COMMENT 'Serialized data from xf_forum_prefix, [group_id][prefix_id] => prefix_id',
  `default_prefix_id` int(10) unsigned NOT NULL DEFAULT '0',
  `default_sort_order` varchar(25) NOT NULL DEFAULT 'last_post_date',
  `default_sort_direction` varchar(5) NOT NULL DEFAULT 'desc',
  `list_date_limit_days` smallint(5) unsigned NOT NULL DEFAULT '0',
  `require_prefix` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `allowed_watch_notifications` varchar(10) NOT NULL DEFAULT 'all',
  `min_tags` smallint(5) unsigned NOT NULL DEFAULT '0',
  
    public $table = 'xf_forum';

    function __construct(){
        parent::__construct();
    }
    
}
?>
