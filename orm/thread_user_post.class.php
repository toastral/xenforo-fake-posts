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
    
    function incrementCount($thread_id, $user_id){
        $q = "SELECT post_count FROM ".$this->table." WHERE thread_id=".$thread_id." AND user_id=".$user_id.";";
        $res = $this->query($q);
        if($res->num_rows){
            $row = $res->fetch_assoc();
            $post_count = intval($row['post_count']);
            $post_count++;
            $q = "UPDATE ".$this->table." SET post_count=".$post_count." WHERE thread_id=".$thread_id." AND user_id=".$user_id.";";
            $this->query($q);
        }else{
            $this->thread_id = $thread_id;
            $this->user_id = $user_id;
            $this->post_count = 1;
            $this->insert();
        }
    }
}
?>