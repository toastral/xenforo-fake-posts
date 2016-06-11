<?php
class ThreadView extends DB{
    public $thread_id;
    public $table = 'xf_thread_view';

    function __construct(){
        parent::__construct();
    }
    
    function insert(){
        $q = sprintf("INSERT INTO ".$this->table." (
            thread_id
        ) VALUES (%d);",
            $this->thread_id
        );
        $this->query($q);
    }
}
?>