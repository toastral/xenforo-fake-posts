<?php
class ThreadUserPost extends DB{
(
  `thread_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `post_count` int(10) unsigned NOT NULL,

    public $table = 'xf_thread_user_post';

    function __construct(){
        parent::__construct();
    }
    
}



?>