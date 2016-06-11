<?php
class SearchIndex extends DB{

  `content_type` varchar(25) NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `title` varchar(250) NOT NULL DEFAULT '',
  `message` mediumtext NOT NULL,
  `metadata` mediumtext NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `item_date` int(10) unsigned NOT NULL,
  `discussion_id` int(10) unsigned NOT NULL DEFAULT '0',

    public $table = 'xf_search_index';

    function __construct(){
        parent::__construct();
    }
    
}
?>