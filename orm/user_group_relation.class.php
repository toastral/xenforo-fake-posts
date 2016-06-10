<?php
class user_group_relation extends db{
    public $user_id;
    public $user_group_id = 2;
    public $is_primary = 1;
    
    public $table = 'xf_user_group_relation';
    
	function __construct(){
		parent::__construct();
	}
    

    function insert(){
		$q = sprintf("INSERT INTO ".$this->table." (user_id, user_group_id, is_primary) VALUES (%d,%d,%d);", 		
			$this->user_id, 
			$this->user_group_id, 
			$this->is_primary
		);
        
		if(!$this->$mysqli->query($q)){
			echo "Mysql failure: (" . $mysqli->errno . ") " . $mysqli->error;
		}
    }
}
?>