<?php
class FakeUser{
    public $user_id;
    
    public $username;
    public $location;
    public $signature;
    
    public $src_avatar = '';
    public $path_to_xenforo_avatars = 'Z:/home/xenf.ru/www/data/avatars';

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
        return $this->user_id;
    }
    
    function loadAvatar(){
        if(strlen($this->src_avatar) <= 0) return;
        $id = $this->user_id;
        
        $new_avatar = $this->path_to_xenforo_avatars;
        $ext = strtolower( pathinfo ( $this->src_avatar , PATHINFO_EXTENSION) );
        $this->createThumb($this->src_avatar, $new_avatar.'/l/0/'.$id.'.'.$ext,  '200', '200');
        $this->createThumb($this->src_avatar, $new_avatar.'/m/0/'.$id.'.'.$ext,  '96', '96');
        $this->createThumb($this->src_avatar, $new_avatar.'/s/0/'.$id.'.'.$ext,  '48', '48');
        
        $User = new User();
        $User->user_id = $id;
        $User->updateAvatartFlages();
    }
    
    function createThumb($path_src, $path_new, $size_x, $size_y){	
        try {
            $img = new abeautifulsite\SimpleImage($path_src);
            $img->best_fit($size_x, $size_y)->save($path_new);
        } catch(Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }	
    }

}
?>