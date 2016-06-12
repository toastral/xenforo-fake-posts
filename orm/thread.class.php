<?php
class Thread extends DB{
    
    public $thread_id; // int(10) unsigned NOT NULL AUTO_INCREMENT,
    public $node_id; // int(10) unsigned NOT NULL,  --- forum_id
    public $title; // varchar(150) NOT NULL,
    public $reply_count=0; // int(10) unsigned NOT NULL DEFAULT '0',
    public $view_count=0; // int(10) unsigned NOT NULL DEFAULT '0',
    public $user_id; // int(10) unsigned NOT NULL,
    public $username; // varchar(50) NOT NULL,
    public $post_date; // int(10) unsigned NOT NULL,
    public $sticky=0; // tinyint(3) unsigned NOT NULL DEFAULT '0',
    public $discussion_state='visible'; // enum('visible','moderated','deleted') NOT NULL DEFAULT 'visible',
    public $discussion_open=1; // tinyint(3) unsigned NOT NULL DEFAULT '1',
    public $discussion_type=''; // varchar(25) NOT NULL DEFAULT '',
    public $first_post_id; // int(10) unsigned NOT NULL,
    public $first_post_likes=0; // int(10) unsigned NOT NULL DEFAULT '0',
    
    public $last_post_date; // int(10) unsigned NOT NULL,
    public $last_post_id; // int(10) unsigned NOT NULL,
    public $last_post_user_id; // int(10) unsigned NOT NULL,
    public $last_post_username; // varchar(50) NOT NULL,
    public $prefix_id=0; // int(10) unsigned NOT NULL DEFAULT '0',
    public $tags='a:0:{}'; // mediumblob NOT NULL,

    public $table = 'xf_thread';

    function __construct(){
        parent::__construct();
    }
    
    function insert(){
        $q = sprintf("INSERT INTO ".$this->table." (
            node_id,
            title,
            reply_count,
            view_count,
            user_id,
            username,
            post_date,
            sticky,
            discussion_state,
            discussion_open,
            discussion_type,
            first_post_id,
            first_post_likes,
            last_post_date,
            last_post_id,
            last_post_user_id,
            last_post_username,
            prefix_id,
            tags
        ) VALUES (%d,'%s',%d,%d,%d,'%s',%d,%d,'%s',%d,'%s',%d,%d,%d,%d,%d,'%s',%d,'%s');",	
            $this->node_id,
            $this->title,
            $this->reply_count,
            $this->view_count,
            $this->user_id,
            $this->username,
            $this->post_date,
            $this->sticky,
            $this->discussion_state,
            $this->discussion_open,
            $this->discussion_type,
            $this->first_post_id,
            $this->first_post_likes,
            $this->last_post_date,
            $this->last_post_id,
            $this->last_post_user_id,
            $this->last_post_username,
            $this->prefix_id,
            $this->tags
        );
        
        $this->query($q);
        $this->thread_id = mysqli_insert_id($this->db);
    }
    
    function updateFirstPostId($first_post_id){
        $q = "UPDATE ".$this->table." SET first_post_id=".$first_post_id." 
        WHERE thread_id=".$this->thread_id.";";
        $this->query($q);
    }
    function updateLastPostId($last_post_id, $last_post_date, $User){
        $q = "UPDATE ".$this->table." SET 
        last_post_id = ".$last_post_id.", 
        last_post_date = ".$last_post_date.", 
        last_post_user_id = ".$User->user_id.", 
        last_post_username = '".$User->username."' 
        WHERE thread_id=".$this->thread_id.";";

        $this->query($q);
    }
    
    function getNodeIdByThreadId($thread_id){
        $q = "SELECT node_id FROM ".$this->table." WHERE thread_id=".$thread_id.";";
        $res = $this->query($q);
        if(!$res->num_rows){
            throw new Exception('Not found node_id by thread_id: '.$q);
        }
        $row = $res->fetch_assoc();
        return $row['node_id'];
    }
        
    function incrementReplyCount($thread_id){
        $q = "SELECT reply_count FROM ".$this->table." WHERE thread_id=".$thread_id.";";
        $res = $this->query($q);
        if($res->num_rows){
            $row = $res->fetch_assoc();
            $reply_count = intval($row['reply_count']);
            $reply_count++;
            $q = "UPDATE ".$this->table." SET reply_count=".$reply_count." WHERE thread_id=".$thread_id.";";
            $this->query($q);
        }else{
            throw new Exception('Not found reply_count by thread_id: '.$q);
        }
    }
    
    function getTitleByThreadId($thread_id){
        $q = "SELECT title FROM ".$this->table." WHERE thread_id=".$thread_id.";";
        $res = $this->query($q);
        if(!$res->num_rows){
            throw new Exception('Not found title by thread_id: '.$q);
        }
        $row = $res->fetch_assoc();
        return $row['title'];
    }
    
    
    
}



?>