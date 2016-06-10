<?php
class user_option{
	
	public $user_id;
	public $alert_optout='';
	
	public $table='xf_user_option';
	
	function __construct(){
		parent::__construct();
	}
	
    function insert(){        
		$q = sprintf("INSERT INTO ".$this->table." (id, alert_optout) VALUES (%d,'%s');", 		
			$this->user_id, 
			$this->alert_optout
		);
        
		if(!$this->$mysqli->query($q)){
			echo "Mysql failure: (" . $mysqli->errno . ") " . $mysqli->error;
		}
    }
	
}



?>