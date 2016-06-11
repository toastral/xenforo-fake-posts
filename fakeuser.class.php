<?php
class FakeUser{
    public $user_id;
    
    public $username;
    public $location;
    public $signature;
    
    public $src_avatar;

    function create(){
        $User = new User();
        $User->username = $this->username;
        $User->validateUserName();
        $User->email = $User->username.'@mail.rrr';
        $User->insert();
        $this->user_id = $User->user_id;

        $UserAuthenticate = new UserAuthenticate();
        $UserAuthenticate->user_id = $User->user_id;
        $UserAuthenticate->insert();

        $UserGroupRelation = new UserGroupRelation();
        $UserGroupRelation->user_id = $User->user_id;
        $UserGroupRelation->insert();

        $UserOption = new UserOption();
        $UserOption->user_id = $User->user_id;
        $UserOption->insert();

        $UserPrivacy = new UserPrivacy();
        $UserPrivacy->user_id = $User->user_id;
        $UserPrivacy->insert();

        $UserProfile = new UserProfile();
        $UserProfile->location = $this->location;
        $UserProfile->signature = $this->signature;
        $UserProfile->user_id = $User->user_id;
        $UserProfile->insert();
    }
    
    function setAvatar(){
        
    }

}
?>