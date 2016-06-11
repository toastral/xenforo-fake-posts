<?php
class SearchIndex extends DB{
    
    public $content_type; // varchar(25) NOT NULL,
    public $content_id; // int(10) unsigned NOT NULL,
    public $title='';
    public $message; // mediumtext NOT NULL,
    public $metadata; // mediumtext NOT NULL,
    public $user_id='0';
    public $item_date; //` int(10) unsigned NOT NULL,
    public $discussion_id='0';
  
    public $table = 'xf_search_index';

    function __construct(){
        parent::__construct();
    }
    
    function insert(){
        $q = sprintf("INSERT INTO ".$this->table." (
            content_type,
            content_id,
            title,
            message,
            metadata,
            user_id,
            item_date,
            discussion_id
        ) VALUES ('%s',%d,'%s','%s','%s',%d,%d,%d);",	
            $this->content_type,
            $this->content_id,
            $this->title,
            $this->message,
            $this->metadata,
            $this->user_id,
            $this->item_date,
            $this->discussion_id
        );
        $this->query($q);
    }
}
?>