<?php
class user_profile extends db{
	
    public $tb_name = '';
    function __construct(){
        parent::__construct();
    }
	
    function lightInsert(){
        $q = "INSERT";
        if(!$this->$mysqli->query($q)){
            echo "Mysql failure: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $this->user_id = mysqli_insert_id();
    }
}
?>