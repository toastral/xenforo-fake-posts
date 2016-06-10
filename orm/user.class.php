<?php
class user extends db{
	public $user_id; // int(10) unsigned NOT NULL AUTO_INCREMENT,
	public $username; // varchar(50) NOT NULL,
    public $email; // varchar(120) NOT NULL,
    public $gender; // enum('','male','female') NOT NULL DEFAULT '' COMMENT 'Leave empty for ''unspecified''',
    //public $custom_title; // varchar(50) NOT NULL DEFAULT '',
    public $language_id; // int(10) unsigned NOT NULL,
    public $style_id = 0; // int(10) unsigned NOT NULL COMMENT '0 = use system default',
    public $timezone; // varchar(50) NOT NULL COMMENT 'Example: ''Europe/London''',
    //public $visible; // tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Show browsing activity to others',
    //public $activity_visible; // tinyint(3) unsigned NOT NULL DEFAULT '1',
    public $user_group_id = 2; // int(10) unsigned NOT NULL,
    public $secondary_group_ids = ''; // varbinary(255) NOT NULL,
    //public $display_style_group_id; // int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'User group ID that provides user styling',
    public $permission_combination_id = 2; // int(10) unsigned NOT NULL,
    //public $message_count; // int(10) unsigned NOT NULL DEFAULT '0',
    //public $conversations_unread; // smallint(5) unsigned NOT NULL DEFAULT '0',
    public $register_date; // int(10) unsigned NOT NULL DEFAULT '0',
    public $last_activity; // int(10) unsigned NOT NULL DEFAULT '0',
    //public $trophy_points; // int(10) unsigned NOT NULL DEFAULT '0',
    //public $alerts_unread; // smallint(5) unsigned NOT NULL DEFAULT '0',
    //public $avatar_date; // int(10) unsigned NOT NULL DEFAULT '0',
    //public $avatar_width; // smallint(5) unsigned NOT NULL DEFAULT '0',
    //public $avatar_height; // smallint(5) unsigned NOT NULL DEFAULT '0',
    //public $gravatar; // varchar(120) NOT NULL DEFAULT '' COMMENT 'If specified, this is an email address corresponding to the user''s ''Gravatar''',
    //public $user_state; // enum('valid','email_confirm','email_confirm_edit','moderated','email_bounce') NOT NULL DEFAULT 'valid',
    //public $is_moderator; // tinyint(3) unsigned NOT NULL DEFAULT '0',
    //public $is_admin; // tinyint(3) unsigned NOT NULL DEFAULT '0',
    //public $is_banned; // tinyint(3) unsigned NOT NULL DEFAULT '0',
    //public $like_count; // int(10) unsigned NOT NULL DEFAULT '0',
    //public $warning_points; // int(10) unsigned NOT NULL DEFAULT '0',
    //public $is_staff; // tinyint(3) unsigned NOT NULL DEFAULT '0',	
	
	public $tb_name = 'xf_user';
	function __construct(){
		parent::__construct();
	}
	
	function lightInsert(){
		$q = "INSERT INTO xf_user (
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
)
		if(!$this->$mysqli->query($q)){
			echo "Не удалось создать таблицу: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		$this->user_id = mysqli_insert_id();
	}
	
}
?>