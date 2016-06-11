<?php
class ThreadUserPost extends DB{
    public $thread_id; // int(10) unsigned NOT NULL,
    public $user_id; // int(10) unsigned NOT NULL,
    public $post_count; // int(10) unsigned NOT NULL,
  
    public $table = 'xf_thread_user_post';
    
    function __construct(){
        parent::__construct();
    }
    function insert(){
        $q = sprintf("INSERT INTO ".$this->table." (
            thread_id,
            user_id,
            post_count
        ) VALUES (%d,%d,%d);",	
            $this->thread_id,
            $this->user_id,
            $this->post_count
        );        
        $this->query($q);
    }
}
?>