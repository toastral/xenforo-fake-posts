<?php
class FakePost{
    // in 
    public $thread_id;
    public $user_id;
    public $message;
    public $post_date;
    
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

    function _insertPostToSearchIndex($post_id, $message, $user_id, $post_date, $thread_id, $node_id){
        $SearchIndex = new SearchIndex();
        $SearchIndex->content_type  = 'post'; // varchar(25) NOT NULL,
        $SearchIndex->content_id    = $post_id; // int(10) unsigned NOT NULL,
        $SearchIndex->title         = '';
        $SearchIndex->message       = $message; // mediumtext NOT NULL,
        $SearchIndex->metadata      = sprintf("_md_user_%d _md_content_post _md_node_%d _md_thread_%d", $user_id, $node_id, $thread_id);
        $SearchIndex->user_id       = $user_id;
        $SearchIndex->item_date     = $post_date; //` int(10) unsigned NOT NULL,
        $SearchIndex->discussion_id = $thread_id;
        $SearchIndex->insert();        
    }
    
    function create(){
        $thread_id  = $this->thread_id;
        $user_id    = $this->user_id;
        $message    = $this->message;
        $post_date  = $this->post_date;
        
        $User = new User();
        $User->user_id = $user_id;
        $User->fetch();
        $post_id = $this->_createPost($thread_id, $post_date, $message, $User );
        
        $Thread = new Thread();
        $Thread->thread_id = $thread_id;
        $node_id = $Thread->getNodeIdByThreadId($thread_id);
        $Thread->incrementReplyCount($thread_id);
        $Thread->updateLastPostId($post_id, $post_date, $User);
        $this->_insertPostToSearchIndex($post_id, $message, $user_id, $post_date, $thread_id, $node_id);
        
        $this->_updateForum($node_id, $post_id, $post_date, $Thread->getTitleByThreadId($thread_id), $User);
        
        $ThreadUserPost = new ThreadUserPost();
        $ThreadUserPost->incrementPostCount($thread_id, $user_id);
        
        $User->incrementMessageCount();        
    }

    
    function _updateForum($node_id, $post_id, $post_date, $thread_title, $User){
        $Forum = new Forum();
        $Forum->node_id = $node_id;
        $Forum->fetch();
        $Forum->message_count = intval($Forum->message_count);
        $Forum->updateAfterPostCreate(++$Forum->message_count, $post_id, $post_date, $thread_title, $User);
    } 
}
?>