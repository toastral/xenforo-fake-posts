<?php
class UserAuthenticate extends DB{
    public $user_id;
    public $scheme_class = 'XenForo_Authentication_Core12';
    public $data;
    public $remember_key;
    
    public $table = 'xf_user_authenticate';
    
	function __construct(){
        parent::__construct();
        $this->data = $this->randHex40byte().$this->randHex40byte().$this->randHex5byte();
        $this->remember_key=$this->randHex40byte();
	}
    
    function insert(){
        $q = sprintf("INSERT INTO ".$this->table." (user_id, scheme_class, data, remember_key) VALUES (%d,'%s', 0x%s, 0x%s);", 		
            $this->user_id, 
            $this->scheme_class, 
            $this->data,
            $this->remember_key
        );
        
        $this->query($q);
    }
    
    function randHex5byte(){
        return sprintf("%04X%04X%02X",rand(0,65535),rand(0,65535),rand(0,255));
    }
    
    function randHex8byte(){
        return sprintf("%04X%04X%04X%04X",rand(0,65535),rand(0,65535),rand(0,65535),rand(0,65535));
    }
    
    function randHex40byte(){
        return $this->randHex8byte().$this->randHex8byte().$this->randHex8byte().$this->randHex8byte().$this->randHex8byte();
    }
}
?>