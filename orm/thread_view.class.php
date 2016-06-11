<?php
class ThreadView extends DB{
(
  `thread_id` int(10) unsigned NOT NULL,

    public $table = 'xf_thread_view';

    function __construct(){
        parent::__construct();
    }
    
}



?>