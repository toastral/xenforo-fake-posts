<?php
class FakeThread{
    // in 
    public $node_id; // forum_id;
    public $user_id;
    public $title;
    public $message;
    public $post_date;

    public $thread_id = 0;
    
    function create(){
        $User = new User();
        $User->user_id = $this->user_id;
        $User->fetch();
		
        $db = DbSingleton::getInstance()->getConnection();
        $User->username = $db->real_escape_string($User->username);
        
        $first_post_id = 0;
        $this->thread_id = $this->_createThread($this->node_id, $this->title, $this->post_date, $first_post_id, $User);
        $first_post_id = $this->_createPost($this->thread_id, $this->post_date, $this->message, $User);
        
        $Thread = new Thread();
        $Thread->thread_id = $this->thread_id;
        $Thread->updateFirstPostId($first_post_id);
        $Thread->updateLastPostId($first_post_id, $this->post_date, $User);
        
        $this->_updateForum($this->node_id, $first_post_id, $this->post_date, $this->title, $User);
        $this->_insertPostToSearchIndex($first_post_id, $this->title, $this->message, $this->user_id, $this->post_date, $this->thread_id, $this->node_id);
        $this->_insertThreadToSearchIndex($this->title, $this->user_id, $this->post_date, $this->thread_id, $this->node_id);
        
        $ThreadUserPost = new ThreadUserPost();
        $ThreadUserPost->incrementPostCount($this->thread_id, $this->user_id);
        
        $User->incrementMessageCount();
    }
    
    function _insertPostToSearchIndex($post_id, $title, $message, $user_id, $post_date, $thread_id, $node_id){
        $SearchIndex = new SearchIndex();
        $SearchIndex->content_type  = 'post'; // varchar(25) NOT NULL,
        $SearchIndex->content_id    = $post_id; // int(10) unsigned NOT NULL,
        $SearchIndex->title         = $title;
        $SearchIndex->message       = $message; // mediumtext NOT NULL,
        $SearchIndex->metadata      = sprintf("_md_user_%d _md_content_post _md_node_%d _md_thread_%d", $user_id, $node_id, $thread_id);
        $SearchIndex->user_id       = $user_id;
        $SearchIndex->item_date     = $post_date; //` int(10) unsigned NOT NULL,
        $SearchIndex->discussion_id = $thread_id;
        $SearchIndex->insert();        
    }
    
    function _insertThreadToSearchIndex($title, $user_id, $post_date, $thread_id, $node_id){
        $SearchIndex = new SearchIndex();
        $SearchIndex->content_type  = 'thread'; // varchar(25) NOT NULL,
        $SearchIndex->content_id    = $thread_id; // int(10) unsigned NOT NULL,
        $SearchIndex->title         = $title;
        $SearchIndex->message       = ''; // mediumtext NOT NULL,
        $SearchIndex->metadata      = sprintf("_md_user_%d _md_content_thread _md_node_%d _md_thread_%d", $user_id, $node_id, $thread_id);
        $SearchIndex->user_id       = $user_id;
        $SearchIndex->item_date     = $post_date; //` int(10) unsigned NOT NULL,
        $SearchIndex->discussion_id = $thread_id;
        $SearchIndex->insert();        
    }    
    
    function _updateForum($node_id, $post_id, $post_date, $thread_title, $User){
        $Forum = new Forum();
        $Forum->node_id = $node_id;
        $Forum->fetch();
        $Forum->discussion_count = intval($Forum->discussion_count);
        $Forum->message_count = intval($Forum->message_count);    
        $Forum->updateAfterThreadCreate(++$Forum->discussion_count, ++$Forum->message_count, $post_id, $post_date, $thread_title, $User);
    }
    
    function _createPost($thread_id, $post_date, $message, $User ){
		
        $Post = new Post();
        $Post->thread_id    = $thread_id;
        $Post->user_id      = $User->user_id;
        $Post->username     = $User->username;
        $Post->post_date    = $post_date;
        $Post->message      = $message;
        $Post->position     = $Post->processNextPositionVal($thread_id);
        $Post->insert();
        return $Post->post_id;
    }
    
    function _createThread($node_id, $title, $post_date, $first_post_id, $User){
        $Thread = new Thread();
        $Thread->node_id    = $node_id;
        $Thread->title      = $title;
        $Thread->user_id    = $User->user_id;
        $Thread->username   = $User->username;
        $Thread->post_date  = $post_date;
        $Thread->first_post_id      = $first_post_id;
        $Thread->last_post_date     = $post_date;
        $Thread->last_post_id       = $first_post_id;
        $Thread->last_post_user_id  = $User->user_id;
        $Thread->last_post_username = $User->username;
        $Thread->insert();
        return $Thread->thread_id;
    }
}
?>