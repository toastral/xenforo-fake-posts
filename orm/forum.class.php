<?php
class Forum extends DB{
    public $node_id; //  int(10) unsigned NOT NULL, PRIMARY KEY (`node_id`) 
    public $discussion_count='0';
    public $message_count='0';
    public $last_post_id='0'; //'Most recent post_id',
    public $last_post_date='0'; // 'Date of most recent post',
    public $last_post_user_id='0';// 'User_id of user posting most recently',
    public $last_post_username=''; // 'Username of most recently-posting user',
    public $last_thread_title=''; // 'Title of thread most recent post is in',
    public $moderate_threads='0';
    public $moderate_replies='0';
    public $allow_posting='1';
    public $allow_poll='1';
    public $count_messages='1'; // 'If not set, messages posted (directly) within this forum will not contribute to user message totals.',
    public $find_new='1'; // 'Include posts from this forum when running /find-new/threads',
    public $prefix_cache=''; // mediumblob NOT NULL COMMENT 'Serialized data from xf_forum_prefix, [group_id][prefix_id] => prefix_id',
    public $default_prefix_id='0';
    public $default_sort_order='last_post_date';
    public $default_sort_direction='desc';
    public $list_date_limit_days='0';
    public $require_prefix='0';
    public $allowed_watch_notifications='all';
    public $min_tags='0';

    public $table = 'xf_forum';

    function __construct(){
        parent::__construct();
    }
    
    function insert(){
        $q = sprintf("INSERT INTO ".$this->table." (
            node_id,
            discussion_count,
            message_count,
            last_post_id,
            last_post_date,
            last_post_user_id,
            last_post_username,
            last_thread_title,
            moderate_threads,
            moderate_replies,
            allow_posting,
            allow_poll,
            count_messages,
            find_new,
            prefix_cache,
            default_prefix_id,
            default_sort_order,
            default_sort_direction,
            list_date_limit_days,
            require_prefix,
            allowed_watch_notifications,
            min_tags
        ) VALUES (%d,%d,%d,%d,%d,%d,'%s','%s',%d,%d,%d,%d,%d,%d,%s,%d,'%s','%s',%d,%d,'%s',%d);",	
            $this->node_id,
            $this->discussion_count,
            $this->message_count,
            $this->last_post_id,
            $this->last_post_date,
            $this->last_post_user_id,
            $this->last_post_username,
            $this->last_thread_title,
            $this->moderate_threads,
            $this->moderate_replies,
            $this->allow_posting,
            $this->allow_poll,
            $this->count_messages,
            $this->find_new,
            $this->prefix_cache,
            $this->default_prefix_id,
            $this->default_sort_order,
            $this->default_sort_direction,
            $this->list_date_limit_days,
            $this->require_prefix,
            $this->allowed_watch_notifications,
            $this->min_tags
        );
        $this->query($q);
    }  
    
    function fetch(){
        $q = "SELECT * FROM ".$this->table." WHERE node_id='".$this->node_id."'";
        $res = $this->query($q);
        $row = $res->fetch_assoc();
        $this->discussion_count = $row['discussion_count'];
        $this->message_count = $row['message_count'];
        $this->last_post_id = $row['last_post_id'];
        $this->last_post_date = $row['last_post_date'];
        $this->last_post_user_id = $row['last_post_user_id'];
        $this->last_post_username = $row['last_post_username'];
        $this->last_thread_title = $row['last_thread_title'];
        $this->moderate_threads = $row['moderate_threads'];
        $this->moderate_replies = $row['moderate_replies'];
        $this->allow_posting = $row['allow_posting'];
        $this->allow_poll = $row['allow_poll'];
        $this->count_messages = $row['count_messages'];
        $this->find_new = $row['find_new'];
        $this->prefix_cache = $row['prefix_cache'];
        $this->default_prefix_id = $row['default_prefix_id'];
        $this->default_sort_order = $row['default_sort_order'];
        $this->default_sort_direction = $row['default_sort_direction'];      
        $this->list_date_limit_days = $row['list_date_limit_days'];
        $this->require_prefix = $row['require_prefix'];
        $this->allowed_watch_notifications = $row['allowed_watch_notifications'];
        $this->min_tags = $row['min_tags'];
    } 
    
    function updateAfterThreadCreate($discussion_count, $message_count, $last_post_id, $last_post_date, $last_thread_title, $User){
        $q = "UPDATE ".$this->table." SET 
        discussion_count = ".$discussion_count.", 
        message_count = ".$message_count.", 
        last_post_id = ".$last_post_id.", 
        last_post_date = ".$last_post_date.", 
        last_post_user_id = ".$User->user_id.", 
        last_post_username = '".$User->username."', 
        last_thread_title = '".$last_thread_title."' 
        WHERE node_id=".$this->node_id.";";     
        $this->query($q);
    }
    
    function updateAfterPostCreate($message_count, $last_post_id, $last_post_date, $last_thread_title, $User){
        $q = "UPDATE ".$this->table." SET 
        message_count = ".$message_count.", 
        last_post_id = ".$last_post_id.", 
        last_post_date = ".$last_post_date.", 
        last_post_user_id = ".$User->user_id.", 
        last_post_username = '".$User->username."', 
        last_thread_title = '".$last_thread_title."' 
        WHERE node_id=".$this->node_id.";";     
        $this->query($q);
    }
    
    function updateLastPostValues($last_post_id, $last_post_date, $last_thread_title, $user_id, $username ){
        $q = "UPDATE ".$this->table." SET
        last_post_id = ".$last_post_id.", 
        last_post_date = ".$last_post_date.", 
        last_post_user_id = ".$user_id.", 
        last_post_username = '".$username."', 
        last_thread_title = '".$last_thread_title."'
        WHERE node_id=".$this->node_id.";";     
        $this->query($q);
    }
    
    function getNodeIdAndTitle(){
        $q = "SELECT node_id, title FROM xf_node WHERE node_type_id=0x466F72756D ;";
        $res = $this->query($q);
        $a_forums = array();
        while($row = $res->fetch_assoc()){
            $a_forums[] = $row;
        }
        return $a_forums;
    }
    
    
}
?>
