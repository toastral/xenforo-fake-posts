<?php
class FakeThread{
    // in 
    public $node_id; // forum_id;
    public $user_id;
    public $title;
    public $message;
    public $post_date;

    public $thread_id;
    
    function create(){
        $User = new User();
        $User->user_id = $this->user_id;
        $User->fetch();
        
        $first_post_id = 0;
        $this->thread_id = $this->_createThread($this->node_id, $this->title, $this->post_date, $first_post_id, $User);
        $first_post_id = $this->_createPost($this->thread_id, $this->post_date, $this->message, $User);
        
        $Thread = new Thread();
        $Thread->thread_id = $this->thread_id;
        $Thread->updateFirstPostId($first_post_id);
        $Thread->updateLastPostId($first_post_id, $this->post_date, $User);
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
    
    function setAvatar(){
        
    }

}
?>