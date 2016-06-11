<?php
class User extends DB{
    public $user_id; // int(10) unsigned NOT NULL AUTO_INCREMENT,
    public $username; // varchar(50) NOT NULL,
    public $email; // varchar(120) NOT NULL,
    public $gender; // enum('','male','female') NOT NULL DEFAULT '' COMMENT 'Leave empty for ''unspecified''',
    public $language_id = 1; // int(10) unsigned NOT NULL,
    public $style_id = 0; // int(10) unsigned NOT NULL COMMENT '0 = use system default',
    public $timezone = 'Europe/Moscow' ; // varchar(50) NOT NULL COMMENT 'Example: ''Europe/London''',
    public $user_group_id = 2; // int(10) unsigned NOT NULL,
    public $secondary_group_ids = ''; // varbinary(255) NOT NULL,
    public $display_style_group_id = 2;
    public $permission_combination_id = 2; // int(10) unsigned NOT NULL,
    public $register_date; // int(10) unsigned NOT NULL DEFAULT '0',
    public $last_activity; // int(10) unsigned NOT NULL DEFAULT '0',

    public $table = 'xf_user';
    
    function __construct(){
        parent::__construct();
        $this->register_date = $this->randRegisterDate();
        $this->last_activity = time()-60*60*24*rand(1,30);
        $this->gender = $this->getRandGender();
    }
    
    function getRandGender(){
        $a = array('','male','female');
        return $a[rand(0,2)];
    }
    
    function randRegisterDate(){
        return time()-60*60*24*30*rand(1,12)*rand(3,5); // минус три-пять лет
    }

    function randLastActivity(){
        return time()-60*60*24*rand(1,30); // от 1 до 30 дней назад
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
        display_style_group_id,
        permission_combination_id, 
        register_date, 
        last_activity)
        VALUES ('%s','%s','%s',%d,%d,'%s',%d,'%s',%d, %d,%d,%d);";
        
        $q = sprintf($q, 		
            $this->username, 
            $this->email, 
            $this->gender,
            $this->language_id, 
            $this->style_id, 
            $this->timezone, 
            $this->user_group_id, 
            $this->secondary_group_ids, 
            $this->display_style_group_id,
            $this->permission_combination_id, 
            $this->register_date, 
            $this->last_activity
        );
        $this->query($q);
        $this->user_id = mysqli_insert_id($this->db);
    }
    
    function validateUserName(){
        $q = "SELECT COUNT(*) c FROM ".$this->table." WHERE username='".$this->username."'";
        $res = $this->query($q);
        $row = $res->fetch_assoc();
        if(intval($row['c'])>0){
            $this->username = $this->username.rand(1,100);
            $this->validateUserName();
        }
    }
    function incrementTrophyPoints($points=1){
        $q = "SELECT trophy_points FROM ".$this->table." WHERE user_id=".$this->user_id;
        $res = $this->query($q);
        $row = $res->fetch_assoc();
        $trophy_points = intval($row['trophy_points'])+1;
        $this->updateTrophyPoints($trophy_points);
    }
    
    function updateTrophyPoints($points=1){
        $q = "UPDATE ".$this->table." SET trophy_points='".$points."' WHERE user_id=".$this->user_id;
        $this->query($q);
    }
    
    function fetch(){
        $q = "SELECT * FROM ".$this->table." WHERE user_id='".$this->user_id."'";
        $res = $this->query($q);
        $row = $res->fetch_assoc();
        $this->username = $row['username'];
        $this->email = $row['email'];
        $this->gender = $row['gender'];
        $this->language_id = $row['language_id'];
        $this->style_id = $row['style_id'];
        $this->timezone = $row['timezone'];
        $this->user_group_id = $row['user_group_id'];
        $this->secondary_group_ids = $row['secondary_group_ids'];
        $this->display_style_group_id = $row['display_style_group_id'];
        $this->permission_combination_id = $row['permission_combination_id'];
        $this->register_date = $row['register_date'];
        $this->last_activity = $row['last_activity'];
    }
}
?>