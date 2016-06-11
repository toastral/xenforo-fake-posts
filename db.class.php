<?php
class DB{
    public $db;

    function __construct(){
        $this->db = DbSingleton::getInstance()->getConnection();
    }
    function query($q){
        $res = $this->db->query($q);
        if(!$res){
            throw new Exception("Mysql query failure: (" . $this->db->errno . ") " . $this->db->error);
        }
        return $res;
    }
}

class DbSingleton {
	private $_link;
	private static $_instance;
	private $db_host  = "localhost:3308";
	private $db_user  = "root";
	private $db_pass  = "mysqlroot";
	private $db_name  = "xenf";

	public static function getInstance() {
		if(!self::$_instance) self::$_instance = new self();
		return self::$_instance;
	}
    private function __construct() {
        $this->_link = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        if(mysqli_connect_error()) {
            throw new Exception("Mysql connect failure: (" . $this->db->errno . ") " . $this->db->error);
        }
    }
	public function getConnection() {
		return $this->_link;
	}
}
?>