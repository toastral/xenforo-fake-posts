<?php
class Thread extends DB{
/*
$node_id = 5;
$user_id = 4;
$post_date = time();
$first_post_id = ?;

$Thread = new Thread();
$Thread->node_id = $node_id;
$Thread->title = 'This is second theme';
$Thread->user_id = $user_id;
$User = new User();
$User->user_id = $user_id;
$User->fetch();

$Thread->username           = $User->username;
$Thread->post_date          = $post_date;
$Thread->first_post_id      = $first_post_id;
$Thread->last_post_date     = $post_date;
$Thread->last_post_id       = $first_post_id;
$Thread->last_post_user_id  = $user_id;
$Thread->last_post_username = $User->username;

INSERT INTO `xf_thread` VALUES (1,5,'This is first theme',0,0,2,'user1',1465551343,0,'visible',1,'',
1,0,1465551343,
1,2,'user1',0,'a:0:{}');
*/

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
        ) VALUES (%d,'%s',%d,%d,%d,'%s',%d,%d,'%s',%d,'%s',%d,%d,%d,%d,%d,'%s',%d,%s);",	
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
}



?>