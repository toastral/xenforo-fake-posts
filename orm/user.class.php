<?php
class user extends db{
    public $user_id; // int(10) unsigned NOT NULL AUTO_INCREMENT,
    public $username; // varchar(50) NOT NULL,
    public $email; // varchar(120) NOT NULL,
    public $gender; // enum('','male','female') NOT NULL DEFAULT '' COMMENT 'Leave empty for ''unspecified''',
    public $language_id; // int(10) unsigned NOT NULL,
    public $style_id = 0; // int(10) unsigned NOT NULL COMMENT '0 = use system default',
    public $timezone; // varchar(50) NOT NULL COMMENT 'Example: ''Europe/London''',
    public $user_group_id = 2; // int(10) unsigned NOT NULL,
    public $secondary_group_ids = ''; // varbinary(255) NOT NULL,
    public $permission_combination_id = 2; // int(10) unsigned NOT NULL,
    public $register_date; // int(10) unsigned NOT NULL DEFAULT '0',
    public $last_activity; // int(10) unsigned NOT NULL DEFAULT '0',

    public $table = 'xf_user';
    
    function __construct(){
        parent::__construct();
    }
	
    function insert(){
        $q = "INSERT INTO ".$this->table." (
        username, 
        email, 
        gender, 
        language_id,
        style_id, 
        timezone, 
        user_group_id, 
        secondary_group_ids, 
        permission_combination_id, 
        register_date, 
        last_activity)
        VALUES ('%s','%s','%s',%d,%d,'%s',%d,%d,%d,%d,%d);";
        
        $q = sprintf($q, 		
            $this->username, 
            $this->email, 
            $this->gender,
            $this->language_id, 
            $this->style_id, 
            $this->timezone, 
            $this->user_group_id, 
            $this->secondary_group_ids, 
            $this->permission_combination_id, 
            $this->register_date, 
            $this->last_activity
        );
        if(!$this->$mysqli->query($q)){
            echo "Mysql failure: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $this->user_id = mysqli_insert_id();
    }	
}
?>