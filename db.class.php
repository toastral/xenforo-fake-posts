<?php
class db{
	public $db_user;
	public $db_name;
	public $db_pass;
	public $db_host;
	
	public $db;
	
	function __construct(){
		$this->db = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
		if ($mysqli->connect_errno) {
			echo "Not connect to MySql: " . $mysqli->connect_error;
		}
	}
}
?>