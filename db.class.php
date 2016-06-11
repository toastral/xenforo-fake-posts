<?php
class DB{
    public $db_user = 'root';
    public $db_name = 'xenf';
    public $db_pass = 'mysqlroot';
    public $db_host = 'localhost:3308';

    public $db;

    function __construct(){
        $this->db = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        if ($this->db->connect_errno) {
            echo "Not connect to MySql: " . $this->db->connect_error;
        }
    }
    function query($q){
        $res = $this->db->query($q);
        if(!$res){
            echo "Mysql failure: (" . $this->db->errno . ") " . $this->db->error;
        }
        return $res;
    }
}
?>