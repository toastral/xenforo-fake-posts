<?php
class user_privacy extends db{
    public $user_id;
    public $allow_post_profile = 'members';
    public $allow_send_personal_conversation='members';

    public $table = 'xf_user_privacy';

    function __construct(){
        parent::__construct();
    }

    function insert(){
        $q = sprintf("INSERT INTO ".$this->table." (user_id, allow_post_profile, allow_send_personal_conversation) VALUES (%d,'%s','%s');",
            $this->user_id, 
            $this->allow_post_profile, 
            $this->allow_send_personal_conversation
        );

        if(!$this->$mysqli->query($q)){
            echo "Mysql failure: (" . $mysqli->errno . ") " . $mysqli->error;
        }
    }
}
?>