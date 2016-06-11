<?php
class UserProfile extends DB{
    public $user_id;
    public $dob_day='0';
    public $dob_month='0';
    public $dob_year='0';
    public $status='';
    public $signature='';
    public $homepage='';
    public $location='';
    public $following='';
    public $ignored='';
    public $csrf_token;
    public $about='';
    public $custom_fields='0x613A303A7B7D';
    public $password_date;

    public $table = 'xf_user_profile';

    function __construct(){
        parent::__construct();
        $this->password_date = time();
        $this->csrf_token = hash ('md5', time().rand(1000,9999) );
        $this->dob_day = rand(1,28);
        $this->dob_month = rand(1,12);
        $this->dob_year = rand(1965,1998);
    }

    function insert(){
        $q = sprintf("INSERT INTO ".$this->table." (
        user_id,
        dob_day,
        dob_month,
        dob_year,
        status,
        signature,
        homepage,
        location,
        following,
        ignored,
        csrf_token,
        about,
        custom_fields,
        password_date
        ) VALUES (%d,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s', %s, %d );",	
            $this->user_id, 
            $this->dob_day,
            $this->dob_month,
            $this->dob_year,
            $this->status,
            $this->signature,
            $this->homepage,
            $this->location,
            $this->following,
            $this->ignored,
            $this->csrf_token,
            $this->about,
            $this->custom_fields,
            $this->password_date
        );
        
        $this->query($q);
    }
}
?>