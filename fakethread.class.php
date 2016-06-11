<?php
class FakeThread{


    function _createThread($node_id, $title, $user_id, $post_date, $first_post_id){
        $Thread = new Thread();
        $Thread->node_id = $node_id;
        $Thread->title = $title;
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
        $Thread->insert();
        return $Thread->thread_id;
    }
    
    function setAvatar(){
        
    }

}
?>