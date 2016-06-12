<?php
class Post extends DB{
    public $post_id; // int(10) unsigned NOT NULL AUTO_INCREMENT,
    public $thread_id;
    public $user_id;
    public $username;
    public $post_date; // int(10) unsigned NOT NULL,
    public $message; // mediumtext NOT NULL,
    public $ip_id='0';
    public $message_state='visible';
    public $attach_count='0';
    public $position; // int(10) unsigned NOT NULL,
    public $likes='0';
    public $like_users='a:0:{}'; // blob NOT NULL,
    public $warning_id='0';
    public $warning_message='';
    public $last_edit_date='0';
    public $last_edit_user_id='0';
    public $edit_count='0';

    public $table = 'xf_post';
    
    function __construct(){
        parent::__construct();
    }
    function insert(){
        $q = sprintf("INSERT INTO ".$this->table." (
            thread_id,
            user_id,
            username,
            post_date,
            message,
            ip_id,
            message_state,
            attach_count,
            position,
            likes,
            like_users,
            warning_id,
            warning_message,
            last_edit_date,
            last_edit_user_id,
            edit_count
        ) VALUES (%d,%d,'%s',%d,'%s',%d,'%s',%d,%d,%d,'%s',%d,'%s',%d,%d,%d);",	
            $this->thread_id,
            $this->user_id,
            $this->username,
            $this->post_date,
            $this->message,
            $this->ip_id,
            $this->message_state,
            $this->attach_count,
            $this->position,
            $this->likes,
            $this->like_users,
            $this->warning_id,
            $this->warning_message,
            $this->last_edit_date,
            $this->last_edit_user_id,
            $this->edit_count
        );
        $this->query($q);
        $this->post_id = mysqli_insert_id($this->db);
    }
    function processNextPositionVal($thread_id){
        $position = $this->getCountPostForThread($thread_id);
        return $position;
    }
    
    function getCountPostForThread($thread_id){
        $q = "SELECT COUNT(thread_id) c_thread FROM ".$this->table." WHERE thread_id=".$thread_id.";";
        $res = $this->query($q);
        $row = $res->fetch_assoc();
        return intval($row['c_thread']);
    }    
    
}
?>